<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Category;
use App\Models\City;
use App\Models\Customer;
use App\Models\Facility;
use App\Models\House;
use App\Models\HouseFacility;
use App\Models\HousePhoto;
use App\Models\Interest;
use App\Models\MortgageRequest;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgentController extends Controller
{
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

        $notifications = SystemNotification::where('user_id', $agent->id)
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
            'recentDeals',
            'notifications'
        ));
    }

    // ─── LISTINGS ───
    public function listings(Request $request)
    {
        $query = House::with(['category', 'city', 'photos'])
            ->where('agent_id', Auth::id());

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

        return view('agent.listings.index', compact('listings', 'categories', 'cities'));
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
            'slug'          => Str::slug($request->name),
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
        $data['slug'] = Str::slug($request->name);

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
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');

        $query = MortgageRequest::with(['house', 'customer', 'interestModel.bank'])
            ->whereIn('house_id', $agentHouseIds);

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
            ->where('agent_id', Auth::id())
            ->with('interest.bank')
            ->get();

        return view('agent.payments.create', compact('houses'));
    }

    public function storeMortgageRequest(Request $request)
    {
        $request->validate([
            // Properti & KPR
            'house_id'           => 'required|exists:houses,id',
            'interest_id'        => 'required|exists:interests,id',
            'dp_percentage'      => 'required|integer|in:5,10,15,20,40,50,60,80',
            'documents'          => 'required|file|mimes:pdf|max:5120',
            // Data Customer
            'nama_lengkap'       => 'required|string|max:255',
            'phone'              => 'required|string|max:20',
            'email'              => 'nullable|email|max:255',
            'nik'                => 'required|string|size:16',
            'tempat_lahir'       => 'nullable|string|max:100',
            'tanggal_lahir'      => 'nullable|date',
            'alamat'             => 'required|string',
            'pekerjaan'          => 'required|string|max:100',
            'penghasilan_bulanan'=> 'required|numeric|min:0',
            'status_pernikahan'  => 'required|in:Belum Menikah,Menikah,Cerai',
        ]);

        $interest = Interest::with('bank', 'house')->findOrFail($request->interest_id);
        $house    = $interest->house;

        abort_if($house->agent_id !== Auth::id(), 403);

        $customer = Customer::create([
            'nama_lengkap'       => $request->nama_lengkap,
            'phone'              => $request->phone,
            'email'              => $request->email,
            'nik'                => $request->nik,
            'tempat_lahir'       => $request->tempat_lahir,
            'tanggal_lahir'      => $request->tanggal_lahir,
            'alamat'             => $request->alamat,
            'pekerjaan'          => $request->pekerjaan,
            'penghasilan_bulanan'=> $request->penghasilan_bulanan,
            'status_pernikahan'  => $request->status_pernikahan,
        ]);

        $dp           = $house->price * ($request->dp_percentage / 100);
        $loan         = $house->price - $dp;
        $n            = $interest->duration * 12;
        $r            = $interest->interest / 100 / 12;
        $monthly      = $r > 0
            ? ($loan * $r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1)
            : $loan / $n;
        $totalWithInt = $monthly * $n;

        $documentPath = $request->file('documents')->store('documents', 'public');

        MortgageRequest::create([
            'customer_id'                => $customer->id,
            'house_id'                   => $house->id,
            'interest_id'                => $interest->id,
            'interest'                   => $interest->interest,
            'duration'                   => $interest->duration,
            'bank_name'                  => $interest->bank->name,
            'dp_percentage'              => $request->dp_percentage,
            'house_price'                => $house->price,
            'dp_total_amount'            => (int) $dp,
            'loan_total_amount'          => (int) $loan,
            'monthly_amount'             => (int) $monthly,
            'loan_interest_total_amount' => (int) $totalWithInt,
            'status'                     => 'Waiting for Bank',
            'documents'                  => $documentPath,
        ]);

        return redirect()->route('agent.payments')
            ->with('success', 'Pengajuan KPR berhasil dibuat!');
    }

    public function showPaymentRequest(MortgageRequest $mortgageRequest)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($mortgageRequest->house_id), 403);

        $mortgageRequest->load(['house', 'customer', 'interestModel.bank', 'installments']);

        return view('agent.payments.show', compact('mortgageRequest'));
    }

    public function submitPaymentRequest(Request $request)
    {
        $request->validate([
            'mortgage_request_id' => 'required|exists:mortgage_requests,id',
            'notes'               => 'nullable|string|max:500',
        ]);

        $mortgageRequest = MortgageRequest::findOrFail($request->mortgage_request_id);
        $agentHouseIds   = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($mortgageRequest->house_id), 403);

        return redirect()->route('agent.payments')
            ->with('success', 'Payment request berhasil disubmit untuk review!');
    }

    // ─── UPLOAD PROOF & DOCS ───
    public function documents(Request $request)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');

        $query = MortgageRequest::with(['house', 'customer'])
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
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('document')->store('documents', 'public');

        $mortgageRequest->update(['documents' => $path]);

        return redirect()->route('agent.documents')
            ->with('success', 'Dokumen berhasil diupload!');
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

        $monthlySales = MortgageRequest::whereIn('house_id', $agentHouseIds)
            ->where('status', 'Approved')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(house_price) as revenue')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
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
            'monthlySales',
            'topProperties',
            'recentTransactions'
        ));
    }
}
