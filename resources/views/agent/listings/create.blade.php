@extends('agent.layouts.app')

@section('title', 'Tambah Listing - Agent Panel')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('agent.listings') }}" class="p-2 rounded-xl border border-[#F2F2F4] hover:bg-[#F2F2F4] transition-colors">
            <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Tambah Listing Baru</h1>
            <p class="text-sm text-[#8F91A2] mt-0.5">Lengkapi informasi properti di bawah ini</p>
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

    <form method="POST" action="{{ route('agent.listings.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h2 class="font-bold text-[#060922] mb-4">Informasi Properti</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Nama Properti <span class="text-[#444444]">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" placeholder="Masukkan nama properti" />
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Harga (IDR) <span class="text-[#444444]">*</span></label>
                                <input type="number" name="price" value="{{ old('price') }}" required class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" placeholder="0" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Sertifikat</label>
                                <input type="text" name="certificate" value="{{ old('certificate') }}" maxlength="100" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" placeholder="Contoh: SHM, SHGB, dll" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Deskripsi <span class="text-[#444444]">*</span></label>
                            <textarea name="about" rows="4" required class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all resize-none" placeholder="Tulis deskripsi properti...">{{ old('about') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div><label class="block text-sm font-semibold text-[#060922] mb-1.5">Kamar Tidur</label><input type="number" name="bedroom" value="{{ old('bedroom') }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" /></div>
                            <div><label class="block text-sm font-semibold text-[#060922] mb-1.5">Kamar Mandi</label><input type="number" name="bathroom" value="{{ old('bathroom') }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" /></div>
                            <div><label class="block text-sm font-semibold text-[#060922] mb-1.5">Luas Tanah (m²)</label><input type="number" name="land_area" value="{{ old('land_area') }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" /></div>
                            <div><label class="block text-sm font-semibold text-[#060922] mb-1.5">Luas Bangunan (m²)</label><input type="number" name="building_area" value="{{ old('building_area') }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" /></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div><label class="block text-sm font-semibold text-[#060922] mb-1.5">Daya Listrik (Watt)</label><input type="number" name="electric" value="{{ old('electric') }}" min="0" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" /></div>
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kategori</label>
                                <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kota</label>
                                <select name="city_id" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                    <option value="">Pilih Kota</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Status Ketersediaan</label>
                            <select name="is_available" class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                <option value="1" {{ old('is_available', '1') == '1' ? 'selected' : '' }}>Tersedia</option>
                                <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h2 class="font-bold text-[#060922] mb-4">Fasilitas</h2>
                    <textarea name="facilities" rows="4" placeholder="Masukkan fasilitas, pisahkan dengan koma" class="w-full p-4 rounded-xl border border-[#F2F2F4] focus:border-[#111111] focus:ring-0 text-sm">{{ old('facilities') }}</textarea>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h2 class="font-bold text-[#060922] mb-4">Thumbnail <span class="text-[#444444]">*</span></h2>
                    <div id="thumbnailPreview" class="relative w-full h-40 rounded-xl border-2 border-dashed border-[#F2F2F4] flex items-center justify-center cursor-pointer hover:border-[#111111]/30 transition-colors overflow-hidden" onclick="document.getElementById('thumbnailInput').click()">
                        <div id="thumbnailPlaceholder" class="text-center">
                            <svg class="w-10 h-10 text-[#8F91A2]/30 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-[#8F91A2]">Klik untuk upload</p>
                            <p class="text-[10px] text-[#8F91A2]/60 mt-1">JPG, PNG, WebP (max 2MB)</p>
                        </div>
                    </div>
                    <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" class="hidden" onchange="previewImage(this, 'thumbnailPreview', 'thumbnailPlaceholder')" />
                </div>
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h2 class="font-bold text-[#060922] mb-4">Foto Tambahan</h2>
                    <div id="photosPreview" class="grid grid-cols-2 gap-2 mb-3"></div>
                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 border-dashed border-[#F2F2F4] cursor-pointer hover:border-[#111111]/30 transition-colors">
                        <svg class="w-5 h-5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span class="text-sm text-[#8F91A2]">Tambah Foto</span>
                        <input type="file" name="photos[]" accept="image/*" multiple class="hidden" onchange="previewMultiple(this)" />
                    </label>
                </div>
                <button type="submit" class="w-full py-3.5 bg-[#060922] text-white font-semibold rounded-xl hover:bg-[#060922]/90 transition-colors text-sm">Simpan Listing</button>
            </div>
        </div>
    </form>
@endsection

@push('after-scripts')
<script>
function previewImage(input, previewId, placeholderId) {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            placeholder.style.display = 'none';
            const existing = preview.querySelector('img');
            if (existing) existing.remove();
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'absolute inset-0 w-full h-full object-cover rounded-xl';
            preview.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function previewMultiple(input) {
    const container = document.getElementById('photosPreview');
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'w-full h-24 rounded-xl overflow-hidden';
                div.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover" />';
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endpush
