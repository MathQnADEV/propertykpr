@extends('agent.layouts.app')

@section('title', 'Edit Listing - Agent Panel')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('agent.listings') }}" class="p-2 rounded-xl border border-[#F2F2F4] hover:bg-[#F2F2F4] transition-colors">
        <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-[#060922]">Edit Listing</h1>
        <p class="text-sm text-[#8F91A2] mt-0.5">{{ $house->name }}</p>
    </div>
</div>

@if($errors->any())
    <div class="bg-[#444444]/5 border border-[#444444]/20 rounded-2xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-[#444444] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="font-semibold text-sm text-[#444444]">Terdapat kesalahan:</p>
                <ul class="mt-1 text-sm text-[#444444]/80 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('agent.listings.update', $house) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <h2 class="font-bold text-[#060922] mb-4">Informasi Properti</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Nama Properti <span class="text-[#444444]">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $house->name) }}" required class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Harga (IDR) <span class="text-[#444444]">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $house->price) }}" required class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Sertifikat</label>
                            <input type="text" name="certificate" value="{{ old('certificate', $house->certificate) }}" maxlength="100"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all"
                                placeholder="Contoh: SHM, SHGB, dll" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Deskripsi <span class="text-[#444444]">*</span></label>
                        <textarea name="about" rows="4" required class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all resize-none">{{ old('about', $house->about) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Developer</label>
                            <select name="developer_id" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                <option value="">Pilih Developer</option>
                                @foreach($developers as $dev)
                                    <option value="{{ $dev->id }}" {{ old('developer_id', $house->developer_id) == $dev->id ? 'selected' : '' }}>{{ $dev->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Cluster</label>
                            <select name="cluster_id" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                <option value="">Pilih Cluster</option>
                                @foreach($clusters as $cl)
                                    <option value="{{ $cl->id }}" {{ old('cluster_id', $house->cluster_id) == $cl->id ? 'selected' : '' }}>{{ $cl->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Tipe</label>
                            <select name="type_id" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                <option value="">Pilih Tipe</option>
                                @foreach($types as $tp)
                                    <option value="{{ $tp->id }}" {{ old('type_id', $house->type_id) == $tp->id ? 'selected' : '' }}>{{ $tp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kamar Tidur</label>
                            <input type="number" name="bedroom" value="{{ old('bedroom', $house->bedroom) }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kamar Mandi</label>
                            <input type="number" name="bathroom" value="{{ old('bathroom', $house->bathroom) }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Luas Tanah (m²)</label>
                            <input type="number" name="land_area" value="{{ old('land_area', $house->land_area) }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Luas Bangunan (m²)</label>
                            <input type="number" name="building_area" value="{{ old('building_area', $house->building_area) }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Daya Listrik (Watt)</label>
                            <input type="number" name="electric" value="{{ old('electric', $house->electric) }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kategori</label>
                            <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $house->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kota</label>
                            <select name="city_id" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                <option value="">Pilih Kota</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $house->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Status Ketersediaan</label>
                        <select name="is_available" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                            <option value="1" {{ old('is_available', $house->is_available) == '1' ? 'selected' : '' }}>Tersedia</option>
                            <option value="0" {{ old('is_available', $house->is_available) == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="longitude" value="{{ old('longitude', $house->longitude ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" placeholder="106.789" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#060922] mb-1.5">Link Google Maps</label>
                    <input type="text" name="maps_url" value="{{ old('maps_url', $house->maps_url ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" placeholder="https://maps.google.com/maps?q=..." />
                    <p class="text-xs text-[#8F91A2] mt-1">Buka Google Maps, klik Share > Embed, copy URL dari src</p>
                </div>
                <h2 class="font-bold text-[#060922] mb-4">Fasilitas</h2>
                <textarea name="facilities" rows="4" placeholder="Masukkan fasilitas, pisahkan dengan koma" class="w-full p-4 rounded-xl border border-[#F2F2F4] focus:border-[#111111] focus:ring-0 text-sm">{{ old('facilities', $house->facilities) }}</textarea>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <h2 class="font-bold text-[#060922] mb-4">Thumbnail</h2>
                @if($house->thumbnail)
                    <img src="{{ Storage::url($house->thumbnail) }}" class="w-full h-40 object-cover rounded-xl mb-3" />
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm" />
            </div>
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <h2 class="font-bold text-[#060922] mb-4">Foto Lainnya</h2>
                @if($house->photos->count() > 0)
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        @foreach($house->photos as $photo)
                            <div class="relative">
                                <img src="{{ Storage::url($photo->photo) }}" class="w-full h-24 object-cover rounded-xl" />
                                <label class="absolute top-1 right-1 bg-white rounded-lg p-1 cursor-pointer">
                                    <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="w-3 h-3" /> Hapus
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="file" name="photos[]" accept="image/*" multiple class="w-full text-sm" />
            </div>
            <button type="submit" class="w-full py-3.5 bg-[#060922] text-white font-semibold rounded-xl hover:bg-[#060922]/90 transition-colors text-sm">
                Simpan Perubahan
            </button>
        </div>
    </div>
</form>
@endsection
