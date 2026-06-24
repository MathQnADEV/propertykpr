<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Commission;
use App\Models\CommissionRequest;
use App\Models\City;
use App\Models\Cluster;
use App\Models\Customer;
use App\Models\Developer;
use App\Models\House;
use App\Models\HouseFacility;
use App\Models\HousePhoto;
use App\Models\Interest;
use App\Models\MortgageDocument;
use App\Models\MortgageRequest;
use App\Models\SystemNotification;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AgentController extends Controller
{
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (House::withTrashed()->where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function dashboard()
    {
        $agent = Auth::user();
        $agentHouseIds = House::where('agent_id', $agent->id)->withTrashed()->pluck('id');
        $totalListings = House::where('agent_id', $agent->id)->where('is_available', true)->count();
        $totalSold = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Approved')->count();
        $totalInProcess = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Waiting for Bank')->count();
        $totalFailed = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Rejected')->count();
        $recentListings = House::with(['category', 'city'])->where('agent_id', $agent->id)->latest()->take(5)->get();
        $recentDeals = MortgageRequest::with(['house', 'customer'])->whereIn('house_id', $agentHouseIds)->latest()->take(5)->get();
        return view('agent.dashboard.index', compact('agent', 'totalListings', 'totalSold', 'totalInProcess', 'totalFailed', 'recentListings', 'recentDeals'));
    }

    public function listings(Request $request)
    {
        $viewAll = $request->get('view') === 'all';
        $query = House::with(['category', 'city', 'photos', 'agent']);
        if (!$viewAll) {
            $query->where('agent_id', Auth::id());
        }
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
        $listings = $query->latest()->paginate(9);
        $categories = Category::all();
        $cities = City::all();
        $owners = $viewAll ? User::role(['agent', 'admin', 'master'])->whereHas('houses')->orderBy('name')->get(['id', 'name']) : collect();
        return view('agent.listings.index', compact('listings', 'categories', 'cities', 'viewAll', 'owners'));
    }

    public function createListing()
    {
        $categories = Category::all();
        $cities = City::all();
        $developers = Developer::all();
        $clusters = Cluster::all();
        $types = Type::all();
        return view('agent.listings.create', compact('categories', 'cities', 'developers', 'clusters', 'types'));
    }

    public function storeListing(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'about' => 'required|string',
            'certificate' => 'required|in:SHM,SHGB,Patches',
            'thumbnail' => 'nullable',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'facilities' => 'nullable|string',
            'developer_id' => 'nullable|exists:developers,id',
            'cluster_id' => 'nullable|exists:clusters,id',
            'type_id' => 'nullable|exists:types,id',
            'category_id' => 'nullable|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'electric' => 'nullable|numeric|min:0',
            'land_area' => 'nullable|numeric|min:0',
            'building_area' => 'nullable|numeric|min:0',
            'bedroom' => 'nullable|numeric|min:0',
            'bathroom' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $thumbnailPath = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('houses', 'public') : null;

        $house = House::create([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'price' => $request->price,
            'about' => $request->about,
            'certificate' => $request->certificate,
            'electric' => $request->electric,
            'land_area' => $request->land_area,
            'building_area' => $request->building_area,
            'bedroom' => $request->bedroom,
            'bathroom' => $request->bathroom,
            'category_id' => $request->category_id,
            'city_id' => $request->city_id,
            'thumbnail' => $thumbnailPath ?? null,
            'is_available' => $request->is_available ?? true,
            'agent_id' => Auth::id(),
            'developer_id' => $request->developer_id,
            'cluster_id' => $request->cluster_id,
            'type_id' => $request->type_id,
            'facilities' => $request->facilities,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('house-photos', 'public');
                HousePhoto::create(['house_id' => $house->id, 'photo' => $photoPath]);
            }
        }

        return redirect()->route('agent.listings')->with('success', 'Listing berhasil ditambahkan!');
    }

    public function showListing(House $house)
    {
        $house->load(['photos', 'category', 'city', 'agent', 'developer', 'cluster', 'type']);
        return view('agent.listings.show', compact('house'));
    }

    public function editListing(House $house)
    {
        abort_if($house->agent_id !== Auth::id(), 403);
        $house->load(['photos', 'category', 'city']);
        $categories = Category::all();
        $cities = City::all();
        $developers = Developer::all();
        $clusters = Cluster::all();
        $types = Type::all();
        return view('agent.listings.edit', compact('house', 'categories', 'cities', 'developers', 'clusters', 'types'));
    }

    public function updateListing(Request $request, House $house)
    {
        abort_if($house->agent_id !== Auth::id(), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'about' => 'required|string',
            'certificate' => 'required|in:SHM,SHGB,Patches',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'integer|exists:house_photos,id',
            'facilities' => 'nullable|string',
            'developer_id' => 'nullable|exists:developers,id',
            'cluster_id' => 'nullable|exists:clusters,id',
            'type_id' => 'nullable|exists:types,id',
            'category_id' => 'nullable|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'electric' => 'nullable|numeric|min:0',
            'land_area' => 'nullable|numeric|min:0',
            'building_area' => 'nullable|numeric|min:0',
            'bedroom' => 'nullable|numeric|min:0',
            'bathroom' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $data = $request->except(['thumbnail', 'photos', 'facilities', 'delete_photos']);
        $data['slug'] = $this->generateUniqueSlug($request->name, $house->id);
        $data['facilities'] = $request->facilities;
        $data['developer_id'] = $request->developer_id;
        $data['cluster_id'] = $request->cluster_id;
        $data['type_id'] = $request->type_id;

        if ($request->hasFile('thumbnail')) {
            if ($house->thumbnail) {
                Storage::disk('public')->delete($house->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('houses', 'public');
        }

        $house->update($data);

        if ($request->filled('delete_photos')) {
            $photosToDelete = HousePhoto::whereIn('id', $request->delete_photos)->where('house_id', $house->id)->get();
            foreach ($photosToDelete as $p) {
                Storage::disk('public')->delete($p->photo);
                $p->delete();
            }
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('house-photos', 'public');
                HousePhoto::create(['house_id' => $house->id, 'photo' => $photoPath]);
            }
        }

        return redirect()->route('agent.listings')->with('success', 'Listing berhasil diperbarui!');
    }

    public function deleteListing(House $house)
    {
        abort_if($house->agent_id !== Auth::id(), 403);
        $house->delete();
        return redirect()->route('agent.listings')->with('success', 'Listing berhasil dihapus!');
    }

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
        $houses = House::where('is_available', true)->with(['interest.bank', 'agent'])->limit(200)->get();
        return view('agent.payments.create', compact('houses'));
    }

    public function storeMortgageRequest(Request $request)
    {
        $paymentType = $request->payment_type ?? 'kpr';
        $request->validate([
            'house_id' => 'required|exists:houses,id',
            'documents' => 'required|file|mimes:pdf|max:5120',
            'nama_lengkap' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'nik' => 'required|string|size:16',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string|max:100',
            'penghasilan_bulanan' => 'required|numeric|min:0',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah,Cerai',
            'interest_id' => 'nullable|exists:interests,id',
        ]);

        $house = House::findOrFail($request->house_id);
        $customer = Customer::create($request->only(['nama_lengkap', 'phone', 'email', 'nik', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'pekerjaan', 'penghasilan_bulanan', 'status_pernikahan']));
        $documentPath = $request->file('documents')->store('documents', 'public');

        MortgageRequest::create([
            'payment_type' => $paymentType,
            'user_id' => Auth::id(),
            'customer_id' => $customer->id,
            'house_id' => $house->id,
            'interest_id' => $request->interest_id,
            'house_price' => $house->price,
            'status' => 'Waiting for Bank',
            'documents' => $documentPath,
            'notes' => $request->notes,
            'dp_percentage' => $request->dp_percentage ?? 0,
        ]);

        return redirect()->route('agent.payments')->with('success', 'Pengajuan berhasil dibuat!');
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
            'notes' => 'nullable|string|max:500',
        ]);
        $mortgageRequest = MortgageRequest::with('house')->findOrFail($request->mortgage_request_id);
        abort_if($mortgageRequest->status !== 'Waiting for Bank', 422);
        $houseName = $mortgageRequest->house?->name ?? ('Properti #' . $mortgageRequest->house_id);
        $agentName = Auth::user()->name;
        $notes = $request->notes ? ' — ' . $request->notes : '';
        $url = '/admin/mortgage-requests/' . $mortgageRequest->id . '/edit';
        $now = now();
        $masters = User::role('master')->select('id')->get();
        if ($masters->isNotEmpty()) {
            SystemNotification::insert($masters->map(fn($m) => [
                'user_id' => $m->id, 'type' => 'payment_submit', 'title' => 'Agent Minta Review Pengajuan',
                'description' => '[Submit] ' . $houseName . ' oleh ' . $agentName . $notes,
                'url' => $url, 'is_read' => false, 'created_at' => $now, 'updated_at' => $now,
            ])->toArray());
        }
        return redirect()->route('agent.payments')->with('success', 'Payment request berhasil disubmit!');
    }

    public function documents(Request $request)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        $query = MortgageRequest::with(['house', 'customer', 'mortgageDocuments'])->whereIn('house_id', $agentHouseIds);
        if ($request->filled('search')) {
            $query->whereHas('house', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        $mortgages = $query->latest()->paginate(10);
        return view('agent.documents.index', compact('mortgages'));
    }

    public function uploadDocument(Request $request, MortgageRequest $mortgageRequest)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($mortgageRequest->house_id), 403);
        $request->validate(['document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', 'document_name' => 'required|string|max:100']);
        $path = $request->file('document')->store('documents', 'local');
        MortgageDocument::create(['mortgage_request_id' => $mortgageRequest->id, 'name' => $request->document_name, 'file_path' => $path]);
        return redirect()->route('agent.documents')->with('success', 'Dokumen berhasil diupload!');
    }

    public function deleteDocument(MortgageDocument $document)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($document->mortgageRequest->house_id), 403);
        Storage::disk('local')->delete($document->file_path);
        $document->delete();
        return back()->with('success', 'Dokumen dihapus.');
    }

    public function downloadDocument(MortgageDocument $document)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($document->mortgageRequest->house_id), 403);
        return Storage::disk('local')->download($document->file_path, $document->name . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION));
    }

    public function deals(Request $request)
    {
        $filter        = $request->get('filter', 'all');
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');

        $query = MortgageRequest::with(['house', 'customer', 'interestModel.bank'])
            ->whereIn('house_id', $agentHouseIds);

        if ($filter === 'sold')       $query->where('status', 'Approved');
        elseif ($filter === 'in_process') $query->where('status', 'Waiting for Bank');
        elseif ($filter === 'failed') $query->where('status', 'Rejected');

        $deals = $query->latest()->paginate(10);

        // Single query for all 3 counts
        $counts = MortgageRequest::whereIn('house_id', $agentHouseIds)
            ->selectRaw("
                SUM(status = 'Approved') as sold,
                SUM(status = 'Waiting for Bank') as in_process,
                SUM(status = 'Rejected') as failed
            ")
            ->first();

        $soldCount      = (int) ($counts->sold ?? 0);
        $inProcessCount = (int) ($counts->in_process ?? 0);
        $failedCount    = (int) ($counts->failed ?? 0);

        return view('agent.deals.index', compact('deals', 'filter', 'soldCount', 'inProcessCount', 'failedCount'));
    }

    public function dealDetails(MortgageRequest $mortgageRequest)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        abort_if(!$agentHouseIds->contains($mortgageRequest->house_id), 403);
        $mortgageRequest->load(['house.photos', 'customer', 'interestModel.bank', 'installments']);
        return view('agent.deals.show', compact('mortgageRequest'));
    }

    public function commissions(Request $request)
    {
        $agentId = Auth::id();
        $agentHouseIds = House::where('agent_id', $agentId)->withTrashed()->pluck('id');
        $baseQuery = Commission::whereHas('mortgageRequest', fn($q) => $q->whereIn('house_id', $agentHouseIds));
        $totalAllTime = (clone $baseQuery)->sum('commission_amount');
        $totalDeals = (clone $baseQuery)->count();
        $totalCommission = $totalAllTime;
        $period = $request->get('period', 'monthly');
        $periodLabel = match ($period) { 'weekly' => 'Minggu', 'yearly' => 'Tahun', default => 'Bulan' };
        $totalCurrentPeriod = match ($period) {
            'weekly' => (clone $baseQuery)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('commission_amount'),
            'yearly' => (clone $baseQuery)->whereYear('created_at', now()->year)->sum('commission_amount'),
            default => (clone $baseQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('commission_amount'),
        };
        $commissions = (clone $baseQuery)->with(['mortgageRequest.house', 'mortgageRequest.customer'])->latest()->paginate(10, ['*'], 'history_page');
        $commissionRequests = CommissionRequest::where('agent_id', $agentId)->with(['mortgageRequest.house', 'mortgageRequest.customer'])->latest()->paginate(10, ['*'], 'req_page');
        $pendingRequestCount = CommissionRequest::where('agent_id', $agentId)->where('status', 'pending')->count();
        return view('agent.commissions.index', compact('totalAllTime', 'totalCurrentPeriod', 'totalDeals', 'totalCommission', 'commissions', 'commissionRequests', 'pendingRequestCount', 'period', 'periodLabel'));
    }

    public function requestCommission()
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        $eligibleDeals = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('status', 'Approved')->doesntHave('commission')->whereDoesntHave('commissionRequests', fn($q) => $q->where('status', 'pending'))->with(['house:id,name,thumbnail,price', 'customer:id,nama_lengkap'])->latest()->paginate(20);
        return view('agent.commissions.request', compact('eligibleDeals'));
    }

    public function storeCommissionRequest(Request $request)
    {
        $agentHouseIds = House::where('agent_id', Auth::id())->withTrashed()->pluck('id');
        $request->validate(['mortgage_request_id' => 'required|exists:mortgage_requests,id', 'notes' => 'nullable|string|max:1000']);
        $mr = MortgageRequest::whereIn('house_id', $agentHouseIds)->where('id', $request->mortgage_request_id)->where('status', 'Approved')->doesntHave('commission')->whereDoesntHave('commissionRequests', fn($q) => $q->where('status', 'pending'))->firstOrFail();
        CommissionRequest::create(['mortgage_request_id' => $mr->id, 'agent_id' => Auth::id(), 'notes' => $request->notes, 'status' => 'pending']);
        return redirect()->route('agent.commissions', ['tab' => 'requests'])->with('success', 'Request komisi dikirim.');
    }

    public function reports()
    {
        $agent = Auth::user();
        $agentHouseIds = House::where("agent_id", $agent->id)->withTrashed()->pluck("id");
        $totalListings = House::where("agent_id", $agent->id)->where("is_available", true)->count();
        $totalSold = MortgageRequest::whereIn("house_id", $agentHouseIds)->where("status", "Approved")->count();
        $totalInProcess = MortgageRequest::whereIn("house_id", $agentHouseIds)->where("status", "Waiting for Bank")->count();
        $totalFailed = MortgageRequest::whereIn("house_id", $agentHouseIds)->where("status", "Rejected")->count();
        $totalRevenue = MortgageRequest::whereIn("house_id", $agentHouseIds)->where("status", "Approved")->sum("house_price");
        $totalCommissionAmount = Commission::where("agent_id", $agent->id)->sum("commission_amount");
        $pendingCommissionReqs = CommissionRequest::where("agent_id", $agent->id)->where("status", "pending")->count();
        $monthlySales = MortgageRequest::whereIn("house_id", $agentHouseIds)->where("status", "Approved")->whereYear("created_at", now()->year)->selectRaw("MONTH(created_at) as month, COUNT(*) as count, SUM(house_price) as revenue")->groupBy("month")->orderBy("month")->get();
        $topProperties = House::withCount(["mortgageRequests" => fn($q) => $q->where("status", "Approved")])->where("agent_id", $agent->id)->orderByDesc("mortgage_requests_count")->take(5)->get();
        $recentTransactions = MortgageRequest::with(["house", "customer"])->whereIn("house_id", $agentHouseIds)->where("status", "Approved")->latest()->take(10)->get();
        return view("agent.reports.index", compact("totalListings", "totalSold", "totalInProcess", "totalFailed", "totalRevenue", "totalCommissionAmount", "pendingCommissionReqs", "monthlySales", "topProperties", "recentTransactions"));
    }

    public function notifications()
    {
        $notifications = SystemNotification::where('user_id', Auth::id())->whereIn('type', ['broadcast', 'direct'])->latest()->paginate(20);
        return view('agent.notifications.index', compact('notifications'));
    }

    public function markAllNotificationsRead()
    {
        SystemNotification::where('user_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    public function markNotificationRead(SystemNotification $notification)
    {
        if ($notification->user_id === Auth::id()) $notification->update(['is_read' => true]);
        return back();
    }

    public function deleteNotification(SystemNotification $notification)
    {
        if ($notification->user_id === Auth::id()) $notification->delete();
        return back()->with('success', 'Notifikasi dihapus');
    }

    public function deleteAllNotifications()
    {
        SystemNotification::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Semua notifikasi dihapus');
    }


    public function propertyBrowse(Request $request)
    {
        $query = House::with(["category", "city", "agent", "photos"])->where("is_available", true);
        if ($request->filled("search")) {
            $query->where("name", "like", "%" . $request->search . "%");
        }
        if ($request->filled("category")) {
            $query->where("category_id", $request->category);
        }
        if ($request->filled("city")) {
            $query->where("city_id", $request->city);
        }
        $properties = $query->latest()->paginate(12);
        $categories = Category::all();
        $cities = City::all();
        return view("agent.property-browse", compact("properties", "categories", "cities"));
    }

    public function activityLog()
    {
        $activities = \Spatie\Activitylog\Models\Activity::where('causer_id', Auth::id())->latest()->paginate(20);
        return view('agent.activity-log', compact('activities'));
    }
}
