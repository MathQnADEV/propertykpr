<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Commission;
use App\Models\CommissionRequest;
use App\Models\City;
use App\Models\Customer;
use App\Models\Facility;
use App\Models\House;
use App\Models\HouseFacility;
use App\Models\HousePhoto;
use App\Models\Interest;
use App\Models\MortgageDocument;
use App\Models\MortgageRequest;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgentController extends Controller
{
    // ─── SLUG HELPER ───
    /**
     * Generate a slug that is guaranteed to be unique in the houses table.
     * Appends -1, -2, … until a free slot is found.
     * Optionally excludes the given house ID (for updates).
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;

        while (
            House::withTrashed()
                ->where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    // ─── DASHBOARD ───
    public function dashboard()
    {
        $agent = Auth::user();
        $agentHouseIds = House::where('agent_id', $agent->id)->withTrashed()->pluck('id');

        $totalListings  = House::where('agent_id', $agent->id)->where('is_available', true)->count();
        $totalSold      = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Approved')->count();
        $totalInProcess = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Waiting for Bank')->count();
        $totalFailed    = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Rejected')->count();

        $recentListings = House::with(['category', 'city'])
            ->where('agent_id', $agent->id)
            ->latest()
            ->take(5)
            ->get();

        $recentDeals = MortgageRequest::with(['house', 'customer'])
            ->whereIn('house_id', $agentHouseIds)
            ->latest()
            ->take(5)
            ->get();

        return view('agent.dashboard.index', compact(
            'agent',
            'totalListings',
            'totalSold',
            'totalInProcess',
            'totalFailed',
            'recentListings',
            'recentDeals'
        ));
    }

    // ─── LISTINGS ───
    public function listings(Request $request)
    {
        $viewAll = $request->get('view') === 'all';

        $query = House::with(['category', 'city', 'photos', 'agent']);
        if (!$viewAll) {
            $query->where('agent_id', Auth::id());
        }

        // Filter per pemilik/agent (hanya di mode "Semua Listing")
        if ($viewAll && $request->filled('agent')) {
            $query->where('agent_id', $request->agent);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('city')) {
            $query->where('city_id', $request->city);
        }

        $listings   = $query->latest()->paginate(9);
        $categories = Category::all();
        $cities     = City::all();

        // Daftar pemilik listing (agent/admin/master) untuk dropdown filter
        $owners = $viewAll
            ? User::role(['agent', 'admin', 'master'])
                ->whereHas('houses')
                ->orderBy('name')
                ->get(['id', 'name'])
            : collect();

        return view('agent.listings.index', compact('listings', 'categories', 'cities', 'viewAll', 'owners'));
    }

    public function createListing()
    {
        $categories = Category::all();
        $cities     = City::all();
        $facilities = Facility::all();

        return view('agent.listings.create', compact('categories', 'cities', 'facilities'));
    }

    public function storeListing(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'about'         => 'required|string',
            'certificate'   => 'required|in:SHM,SHGB,Patches',
            'electric'      => 'required|numeric|min:0',
            'land_area'     => 'required|numeric|min:0',
            'building_area' => 'required|numeric|min:0',
            'bedroom'       => 'required|numeric|min:0',
            'bathroom'      => 'required|numeric|min:0',
            'category_id'   => 'required|exists:categories,id',
            'city_id'       => 'required|exists:cities,id',
            'thumbnail'     => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photos.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'facilities'    => 'nullable|array',
            'facilities.*'  => 'exists:facilities,id',
            'is_available'  => 'required|boolean',
        ]);

        $thumbnailPath = $request->file('thumbnail')->store('houses', 'public');

        $house = House::create([
            'name'          => $request->name,
            'slug'          => $this->generateUniqueSlug($request->name),
            'price'         => $request->price,
            'about'         => $request->about,
            'certificate'   => $request->certificate,
            'electric'      => $request->electric,
            'land_area'     => $request->land_area,
            'building_area' => $request->building_area,
            'bedroom'       => $request->bedroom,
            'bathroom'      => $request->bathroom,
            'category_id'   => $request->category_id,
            'city_id'       => $request->city_id,
            'thumbnail'     => $thumbnailPath,
            'is_available'  => $request->is_available,
            'agent_id'      => Auth::id(),
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('house-photos', 'public');
                HousePhoto::create([
                    'house_id' => $house->id,
                    'photo'    => $photoPath,
                ]);
            }
        }

        if ($request->filled('facilities')) {
            foreach ($request->facilities as $facilityId) {
                HouseFacility::create([
                    'house_id'    => $house->id,
                    'facility_id' => $facilityId,
                ]);
            }
        }

        return redirect()->route('agent.listings')
            ->with('success', 'Listing berhasil ditambahkan!');
    }

    public function editListing(House $house)
    {
        abort_if($house->agent_id !== Auth::id(), 403);

        $house->load(['photos', 'facilities', 'category', 'city']);
        $categories = Category::all();
        $cities     = City::all();
        $facilities = Facility::all();

        return view('agent.listings.edit', compact('house', 'categories', 'cities', 'facilities'));
    }

    public function updateListing(Request $request, House $house)
    {
        abort_if($house->agent_id !== Auth::id(), 403);

        $thumbnailRules = $request->input('remove_thumbnail') == '1'
            ? ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
            : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];

        $request->validate([
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'about'            => 'required|string',
            'certificate'      => 'required|in:SHM,SHGB,Patches',
            'electric'         => 'required|numeric|min:0',
            'land_area'        => 'required|numeric|min:0',
            'building_area'    => 'required|numeric|min:0',
            'bedroom'          => 'required|integer|min:0',
            'bathroom'         => 'required|integer|min:0',
            'category_id'      => 'required|exists:categories,id',
            'city_id'          => 'required|exists:cities,id',
            'thumbnail'        => $thumbnailRules,
            'remove_thumbnail' => 'nullable|in:0,1',
            'photos.*'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'delete_photos'    => 'nullable|array',
            'delete_photos.*'  => 'integer|exists:house_photos,id',
            'facilities'       => 'nullable|array',
            'facilities.*'     => 'exists:facilities,id',
            'is_available'     => 'required|boolean',
        ], [
            'name.required'          => 'Nama properti wajib diisi.',
            'price.required'         => 'Harga wajib diisi.',
            'price.numeric'          => 'Harga harus berupa angka.',
            'price.min'              => 'Harga tidak boleh negatif.',
            'about.required'         => 'Deskripsi properti wajib diisi.',
            'certificate.required'   => 'Sertifikat wajib dipilih.',
            'electric.required'      => 'Daya listrik wajib diisi.',
            'electric.numeric'       => 'Daya listrik harus berupa angka.',
            'land_area.required'     => 'Luas tanah wajib diisi.',
            'land_area.numeric'      => 'Luas tanah harus berupa angka.',
            'building_area.required' => 'Luas bangunan wajib diisi.',
            'building_area.numeric'  => 'Luas bangunan harus berupa angka.',
            'bedroom.required'       => 'Jumlah kamar tidur wajib diisi.',
            'bathroom.required'      => 'Jumlah kamar mandi wajib diisi.',
            'category_id.required'   => 'Kategori wajib dipilih.',
            'city_id.required'       => 'Kota wajib dipilih.',
            'thumbnail.required'     => 'Thumbnail wajib diisi. Upload gambar baru jika ingin menghapus thumbnail lama.',
            'thumbnail.image'        => 'File thumbnail harus berupa gambar.',
            'thumbnail.mimes'        => 'Thumbnail harus berformat JPG, JPEG, PNG, atau WebP.',
            'thumbnail.max'          => 'Ukuran thumbnail maksimal 2MB.',
            'photos.*.image'         => 'File foto harus berupa gambar.',
            'photos.*.mimes'         => 'Foto harus berformat JPG, JPEG, PNG, atau WebP.',
            'photos.*.max'           => 'Ukuran setiap foto maksimal 2MB.',
            'is_available.required'  => 'Status ketersediaan wajib dipilih.',
        ]);

        $data         = $request->except(['thumbnail', 'photos', 'facilities', 'remove_thumbnail', 'delete_photos']);
        $data['slug'] = $this->generateUniqueSlug($request->name, $house->id);

        if ($request->input('remove_thumbnail') == '1') {
            if ($house->thumbnail) {
                Storage::disk('public')->delete($house->thumbnail);
            }
            $data['thumbnail'] = null;
        } elseif ($request->hasFile('thumbnail')) {
            if ($house->thumbnail) {
                Storage::disk('public')->delete($house->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('houses', 'public');
        }

        $house->update($data);

        if ($request->filled('delete_photos')) {
            $photosToDelete = HousePhoto::whereIn('id', $request->delete_photos)
                ->where('house_id', $house->id)
                ->get();
            foreach ($photosToDelete as $p) {
                Storage::disk('public')->delete($p->photo);
                $p->delete();
            }
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('house-photos', 'public');
                HousePhoto::create([
                    'house_id' => $house->id,
                    'photo'    => $photoPath,
                ]);
            }
        }

        if ($request->filled('facilities')) {
            $house->facilities()->delete();
            foreach ($request->facilities as $facilityId) {
                HouseFacility::create([
                    'house_id'    => $house->id,
                    'facility_id' => $facilityId,
                ]);
            }
        }

        return redirect()->route('agent.listings')
            ->with('success', 'Listing berhasil diperbarui!');
    }

    public function deleteListing(House $house)
    {
        abort_if($house->agent_id !== Auth::id(), 403);

        $house->delete();

        return redirect()->route('agent.listings')
            ->with('success', 'Listing berhasil dihapus!');
    }

    // ─── PAYMENT REQUESTS ───
    public function paymentRequests(Request $request)
    {
        $query = MortgageRequest::with(['house.agent', 'customer', 'interestModel.bank']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('house', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('customer', function ($q2) use ($request) {
                    $q2->where('nama_lengkap', 'like', '%' . $request->search . '%');
                });
            });
        }

        $paymentRequests = $query->latest()->paginate(10);

        return view('agent.payments.index', compact('paymentRequests'));
    }

    public function createMortgageRequest()
    {
        $houses = House::where('is_available', true)
            ->with(['interest.bank', 'agent'])
            ->get();

        return view('agent.payments.create', compact('houses'));
    }

    public function storeMortgageRequest(Request $request)
    {
        $KREDIT_TYPES = ['kpr', 'kpa', 'kpt', 'kpg'];
        $paymentType  = in_array($request->payment_type, $KREDIT_TYPES)
            ? $request->payment_type
            : ($request->payment_type === 'sewa' ? 'sewa' : 'cash');

        $commonCustomerRules = [
            'house_id'            => 'required|exists:houses,id',
            'documents'           => 'required|file|mimes:pdf|max:5120',
            'nama_lengkap'        => 'required|string|max:255',
            'phone'               => 'required|string|max:20',
            'email'               => 'nullable|email|max:255',
            'nik'                 => 'required|string|size:16',
            'tempat_lahir'        => 'nullable|string|max:100',
            'tanggal_lahir'       => 'nullable|date',
            'alamat'              => 'required|string',
            'pekerjaan'           => 'required|string|max:100',
            'penghasilan_bulanan' => 'required|numeric|min:0',
            'status_pernikahan'   => 'required|in:Belum Menikah,Menikah,Cerai',
        ];

        if (in_array($paymentType, $KREDIT_TYPES)) {
            $request->validate(array_merge($commonCustomerRules, [
                'interest_id' => 'required|exists:interests,id',
            ]));
        } else {
            $request->validate($commonCustomerRules);
        }

        $house = House::findOrFail($request->house_id);

        $customer = Customer::create([
            'nama_lengkap'        => $request->nama_lengkap,
            'phone'               => $request->phone,
            'email'               => $request->email,
            'nik'                 => $request->nik,
            'tempat_lahir'        => $request->tempat_lahir,
            'tanggal_lahir'       => $request->tanggal_lahir,
            'alamat'              => $request->alamat,
            'pekerjaan'           => $request->pekerjaan,
            'penghasilan_bulanan' => $request->penghasilan_bulanan,
            'status_pernikahan'   => $request->status_pernikahan,
        ]);

        $documentPath = $request->file('documents')->store('documents', 'public');

        if (in_array($paymentType, $KREDIT_TYPES)) {
            $interest     = Interest::with('bank')->findOrFail($request->interest_id);
            $dpPct        = (float) ($request->dp_percentage ?? 0);
            $dp           = $house->price * ($dpPct / 100);
            $loan         = $house->price - $dp;
            $n            = $interest->duration * 12;
            $r            = $interest->interest / 100 / 12;
            $monthly      = $r > 0
                ? ($loan * $r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1)
                : ($n > 0 ? $loan / $n : 0);
            $totalWithInt = $monthly * $n;

            MortgageRequest::create([
                'payment_type'               => $paymentType,
                'user_id'                    => Auth::id(),
                'customer_id'                => $customer->id,
                'house_id'                   => $house->id,
                'interest_id'                => $interest->id,
                'interest'                   => $interest->interest,
                'duration'                   => $interest->duration,
                'bank_name'                  => $interest->bank->name,
                'dp_percentage'              => (int) round($dpPct),
                'house_price'                => $house->price,
                'dp_total_amount'            => (int) $dp,
                'loan_total_amount'          => (int) $loan,
                'monthly_amount'             => (int) $monthly,
                'loan_interest_total_amount' => (int) $totalWithInt,
                'status'                     => 'Waiting for Bank',
                'documents'                  => $documentPath,
                'notes'                      => $request->notes,
            ]);

            return redirect()->route('agent.payments')
                ->with('success', 'Pengajuan ' . strtoupper($paymentType) . ' berhasil dibuat!');
        }

        // Cash / Sewa path
        $label = $paymentType === 'sewa' ? 'Sewa' : 'Cash';
        MortgageRequest::create([
            'payment_type'               => $paymentType,
            'user_id'                    => Auth::id(),
            'customer_id'                => $customer->id,
            'house_id'                   => $house->id,
            'interest_id'                => null,
            'interest'                   => 0,
            'duration'                   => 0,
            'bank_name'                  => $label,
            'dp_percentage'              => 100,
            'house_price'                => $house->price,
            'dp_total_amount'            => $house->price,
            'loan_total_amount'          => 0,
            'monthly_amount'             => 0,
            'loan_interest_total_amount' => $house->price,
            'status'                     => 'Waiting for Bank',
            'documents'                  => $documentPath,
            'notes'                      => $request->notes,
        ]);

        return redirect()->route('agent.payments')
            ->with('success', 'Pengajuan Cash berhasil dibuat!');
    }

    public function showPaymentRequest(MortgageRequest $mortgageRequest)
    {
        $mortgageRequest->load(['house', 'customer', 'interestModel.bank', 'installments']);

        return view('agent.payments.show', compact('mortgageRequest'));
    }

    public function submitPaymentRequest(Request $request)
    {
        $request->validate([
            'mortgage_request_id' => 'required|exists:mortgage_requests,id',
            'notes'               => 'nullable|string|max:500',
        ]);

        $mortgageRequest = MortgageRequest::with('house')->findOrFail($request->mortgage_request_id);
        abort_if($mortgageRequest->status !== 'Waiting for Bank', 422);

        // Notify all master users that the agent is requesting a review
        $houseName  = $mortgageRequest->house?->name ?? ('Properti #' . $mortgageRequest->house_id);
        $agentName  = Auth::user()->name;
        $notes      = $request->notes ? ' — ' . $request->notes : '';
        $url        = '/admin/mortgage-requests/' . $mortgageRequest->id . '/edit';
        $now        = now();

        $masters = User::role('master')->select('id')->get();
        if ($masters->isNotEmpty()) {
            SystemNotification::insert(
                $masters->map(fn ($m) => [
                    'user_id'     => $m->id,
                    'type'        => 'payment_submit',
                    'title'       => 'Agent Minta Review Pengajuan',
                    'description' => '[Submit] ' . $houseName . ' oleh ' . $agentName . $notes,
                    'url'         => $url,
                    'is_read'     => false,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ])->toArray()
            );
        }

        return redirect()->route('agent.payments')
            ->with('success', 'Payment request berhasil disubmit. Admin akan segera meninjau!');
    }

    // ─── UPLOAD PROOF & DOCS ───
    public function documents(Request $request)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');

        $query = MortgageRequest::with(['house', 'customer', 'mortgageDocuments'])
            ->whereIn('house_id', $agentHouseIds);

        if ($request->filled('search')) {
            $query->whereHas('house', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $mortgages = $query->latest()->paginate(10);

        return view('agent.documents.index', compact('mortgages'));
    }

    public function uploadDocument(Request $request, MortgageRequest $mortgageRequest)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($mortgageRequest->house_id), 403);

        $request->validate([
            'document'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'document_name' => 'required|string|max:100',
        ]);

        $path = $request->file('document')->store('documents', 'local');

        MortgageDocument::create([
            'mortgage_request_id' => $mortgageRequest->id,
            'name'                => $request->document_name,
            'file_path'           => $path,
        ]);

        return redirect()->route('agent.documents')
            ->with('success', 'Dokumen "' . $request->document_name . '" berhasil diupload!');
    }

    public function deleteDocument(MortgageDocument $document)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($document->mortgageRequest->house_id), 403);

        \Illuminate\Support\Facades\Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function downloadDocument(MortgageDocument $document)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($document->mortgageRequest->house_id), 403);

        return \Illuminate\Support\Facades\Storage::disk('local')->download(
            $document->file_path,
            $document->name . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION)
        );
    }

    // ─── DEALS ───
    public function deals(Request $request)
    {
        $filter        = $request->get('filter', 'all');
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');

        $query = MortgageRequest::with(['house', 'customer', 'interestModel.bank'])
            ->whereIn('house_id', $agentHouseIds);

        if ($filter === 'sold') {
            $query->where('status', 'Approved');
        } elseif ($filter === 'in_process') {
            $query->where('status', 'Waiting for Bank');
        } elseif ($filter === 'failed') {
            $query->where('status', 'Rejected');
        }

        $deals = $query->latest()->paginate(10);

        $soldCount      = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Approved')->count();
        $inProcessCount = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Waiting for Bank')->count();
        $failedCount    = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Rejected')->count();

        return view('agent.deals.index', compact(
            'deals',
            'filter',
            'soldCount',
            'inProcessCount',
            'failedCount'
        ));
    }

    public function dealDetails(MortgageRequest $mortgageRequest)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($mortgageRequest->house_id), 403);

        $mortgageRequest->load(['house.photos', 'house.facilities.facility', 'customer', 'interestModel.bank', 'installments']);

        return view('agent.deals.show', compact('mortgageRequest'));
    }

    // ─── COMMISSIONS & INCOME (unified) ───────────────────────────────────────
    public function commissions(Request $request)
    {
        $tab           = $request->get('tab', 'income');
        $period        = $request->get('period', 'monthly');
        $agentId       = Auth::id();
        $agentHouseIds = House::where('agent_id', $agentId)->withTrashed()->pluck('id');

        $baseQuery = Commission::whereHas('mortgageRequest', fn ($q) => $q->whereIn('house_id', $agentHouseIds));

        // ── Summary (always computed) ────────────────────────────────────────
        $totalAllTime = (clone $baseQuery)->sum('commission_amount');
        $totalDeals   = (clone $baseQuery)->count();

        $periodLabel = match ($period) {
            'weekly' => 'Minggu',
            'yearly' => 'Tahun',
            default  => 'Bulan',
        };

        $totalCurrentPeriod = match ($period) {
            'weekly' => (clone $baseQuery)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('commission_amount'),
            'yearly' => (clone $baseQuery)->whereYear('created_at', now()->year)->sum('commission_amount'),
            default  => (clone $baseQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('commission_amount'),
        };

        // ── Income breakdown ─────────────────────────────────────────────────
        $breakdown = match ($period) {
            'weekly' => (clone $baseQuery)
                ->selectRaw('YEARWEEK(created_at, 1) as period_key, MIN(created_at) as period_start, SUM(commission_amount) as total, COUNT(*) as deal_count')
                ->groupByRaw('YEARWEEK(created_at, 1)')
                ->orderByRaw('YEARWEEK(created_at, 1) DESC')
                ->limit(12)->get()
                ->map(fn ($r) => tap($r, fn ($r) => $r->period_label = 'Minggu ' . \Carbon\Carbon::parse($r->period_start)->format('d M Y'))),

            'yearly' => (clone $baseQuery)
                ->selectRaw('YEAR(created_at) as period_key, MIN(created_at) as period_start, SUM(commission_amount) as total, COUNT(*) as deal_count')
                ->groupByRaw('YEAR(created_at)')
                ->orderByRaw('YEAR(created_at) DESC')
                ->limit(5)->get()
                ->map(fn ($r) => tap($r, fn ($r) => $r->period_label = 'Tahun ' . $r->period_key)),

            default => (clone $baseQuery)
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as period_key, MIN(created_at) as period_start, SUM(commission_amount) as total, COUNT(*) as deal_count')
                ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')
                ->orderByRaw('DATE_FORMAT(created_at, "%Y-%m") DESC')
                ->limit(12)->get()
                ->map(fn ($r) => tap($r, fn ($r) => $r->period_label = \Carbon\Carbon::parse($r->period_start)->translatedFormat('F Y'))),
        };

        // ── Commission history ───────────────────────────────────────────────
        $commissions = (clone $baseQuery)
            ->with(['mortgageRequest.house', 'mortgageRequest.customer'])
            ->latest()
            ->paginate(10, ['*'], 'history_page');

        $totalCommission = $totalAllTime; // alias

        // ── Commission requests ──────────────────────────────────────────────
        $commissionRequests = CommissionRequest::where('agent_id', $agentId)
            ->with(['mortgageRequest.house', 'mortgageRequest.customer'])
            ->latest()
            ->paginate(10, ['*'], 'req_page');

        $pendingRequestCount = CommissionRequest::where('agent_id', $agentId)
            ->where('status', 'pending')
            ->count();

        // ── Status per properti terjual ──────────────────────────────────────
        $approvedBase = MortgageRequest::whereIn('house_id', $agentHouseIds)
            ->where('status', 'Approved');

        $statusKomisiCount   = (clone $approvedBase)->has('commission')->count();
        $statusMenungguCount = (clone $approvedBase)->doesntHave('commission')
            ->whereHas('commissionRequests', fn ($q) => $q->where('status', 'pending'))
            ->count();
        $statusBelumCount    = (clone $approvedBase)->doesntHave('commission')
            ->whereDoesntHave('commissionRequests', fn ($q) => $q->where('status', 'pending'))
            ->count();

        $dealStatus = (clone $approvedBase)
            ->with([
                'house:id,name,thumbnail,price',
                'customer:id,nama_lengkap',
                'commission:id,mortgage_request_id,commission_amount',
                'activePendingCommissionRequest',
            ])
            ->latest()
            ->paginate(10, ['*'], 'status_page');

        return view('agent.commissions.index', compact(
            'tab', 'period', 'periodLabel',
            'totalAllTime', 'totalCurrentPeriod', 'totalDeals', 'totalCommission',
            'breakdown', 'commissions', 'commissionRequests', 'pendingRequestCount',
            'dealStatus', 'statusKomisiCount', 'statusMenungguCount', 'statusBelumCount'
        ));
    }

    // ─── COMMISSION REQUEST ───────────────────────────────────────────────────
    public function requestCommission()
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');

        // Eligible: Approved + no commission + no pending request
        $eligibleDeals = MortgageRequest::whereIn('house_id', $agentHouseIds)
            ->where('status', 'Approved')
            ->doesntHave('commission')
            ->whereDoesntHave('commissionRequests', fn ($q) => $q->where('status', 'pending'))
            ->with(['house:id,name,thumbnail,price', 'customer:id,nama_lengkap'])
            ->latest()
            ->paginate(20, ['*'], 'eligible_page');

        return view('agent.commissions.request', compact('eligibleDeals'));
    }

    public function storeCommissionRequest(Request $request)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');

        $request->validate([
            'mortgage_request_id' => ['required', 'exists:mortgage_requests,id'],
            'notes'               => ['nullable', 'string', 'max:1000'],
        ]);

        $mr = MortgageRequest::whereIn('house_id', $agentHouseIds)
            ->where('id', $request->mortgage_request_id)
            ->where('status', 'Approved')
            ->doesntHave('commission')
            ->whereDoesntHave('commissionRequests', fn ($q) => $q->where('status', 'pending'))
            ->firstOrFail();

        $commissionRequest = CommissionRequest::create([
            'mortgage_request_id' => $mr->id,
            'agent_id'            => Auth::id(),
            'notes'               => $request->notes,
            'status'              => 'pending',
        ]);

        // Notifikasi ke master — bulk insert (1 query, bukan N queries)
        $houseName = $mr->house?->name ?? ('Properti #' . $mr->house_id);
        $now       = now();
        $masters   = User::role('master')->select('id')->get();
        if ($masters->isNotEmpty()) {
            SystemNotification::insert(
                $masters->map(fn ($master) => [
                    'user_id'     => $master->id,
                    'type'        => 'commission_request',
                    'title'       => 'Request Komisi Baru',
                    'description' => '[Request] ' . $houseName . ' dari ' . auth()->user()->name,
                    'url'         => '/admin/commission-requests',
                    'is_read'     => false,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ])->toArray()
            );
        }

        return redirect()->route('agent.commissions', ['tab' => 'requests'])
            ->with('success', 'Request komisi berhasil dikirim. Menunggu konfirmasi master.');
    }

    // ─── REPORTS ───
    public function reports()
    {
        $agent         = Auth::user();
        $agentHouseIds = House::where('agent_id', $agent->id)->withTrashed()->pluck('id');

        $totalListings  = House::where('agent_id', $agent->id)->where('is_available', true)->count();
        $totalSold      = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Approved')->count();
        $totalInProcess = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Waiting for Bank')->count();
        $totalFailed    = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Rejected')->count();
        $totalRevenue   = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Approved')->sum('house_price');

        // ── Commission stats ──────────────────────────────────────────────
        $totalWithCommission    = MortgageRequest::whereIn('house_id', $agentHouseIds)
            ->where('status', 'Approved')
            ->has('commission')
            ->count();

        $totalWithoutCommission = MortgageRequest::whereIn('house_id', $agentHouseIds)
            ->where('status', 'Approved')
            ->doesntHave('commission')
            ->count();

        $totalCommissionAmount  = Commission::where('agent_id', $agent->id)->sum('commission_amount');

        $pendingCommissionReqs  = CommissionRequest::where('agent_id', $agent->id)
            ->where('status', 'pending')
            ->count();

        // Group by YEAR + MONTH and filter to current year to avoid cross-year data merging
        $monthlySales = MortgageRequest::whereIn('house_id', $agentHouseIds)
            ->where('status', 'Approved')
            ->whereYear('created_at', now()->year)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, SUM(house_price) as revenue')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        $topProperties = House::withCount(['mortgageRequests' => function ($q) {
                $q->where('status', 'Approved');
            }])
            ->where('agent_id', $agent->id)
            ->orderByDesc('mortgage_requests_count')
            ->take(5)
            ->get();

        $recentTransactions = MortgageRequest::with(['house', 'customer'])
            ->whereIn('house_id', $agentHouseIds)
            ->where('status', 'Approved')
            ->latest()
            ->take(10)
            ->get();

        return view('agent.reports.index', compact(
            'totalListings',
            'totalSold',
            'totalInProcess',
            'totalFailed',
            'totalRevenue',
            'totalWithCommission',
            'totalWithoutCommission',
            'totalCommissionAmount',
            'pendingCommissionReqs',
            'monthlySales',
            'topProperties',
            'recentTransactions'
        ));
    }
}
