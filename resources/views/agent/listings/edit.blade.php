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
        <div class="bg-[#FF3E3E]/5 border border-[#FF3E3E]/20 rounded-2xl p-4 mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 text-[#FF3E3E] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div>
                <p class="text-sm font-semibold text-[#FF3E3E] mb-1">Ada {{ $errors->count() }} kesalahan yang perlu diperbaiki:</p>
                <ul class="text-sm text-[#FF3E3E]/80 space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @php
        $inputBase = 'w-full px-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 focus:ring-[#3F52FF]/20 focus:border-[#3F52FF] transition-all';
        $inputOk  = 'border-[#F2F2F4]';
        $inputErr = 'border-[#FF3E3E] bg-[#FF3E3E]/5';
    @endphp

    <form method="POST" action="{{ route('agent.listings.update', $house) }}" enctype="multipart/form-data" id="editListingForm">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h2 class="font-bold text-[#060922] mb-4">Informasi Properti</h2>
                    <div class="space-y-4">

                        {{-- Nama Properti --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Nama Properti <span class="text-[#FF3E3E]">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $house->name) }}"
                                class="{{ $inputBase }} {{ $errors->has('name') ? $inputErr : $inputOk }}" />
                            @error('name')
                                <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Harga --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Harga (IDR) <span class="text-[#FF3E3E]">*</span></label>
                                <input type="number" name="price" value="{{ old('price', $house->price) }}" min="0"
                                    class="{{ $inputBase }} {{ $errors->has('price') ? $inputErr : $inputOk }}" />
                                @error('price')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Sertifikat --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Sertifikat <span class="text-[#FF3E3E]">*</span></label>
                                <select name="certificate"
                                    class="{{ $inputBase }} bg-white {{ $errors->has('certificate') ? $inputErr : $inputOk }}">
                                    <option value="SHM"     {{ old('certificate', $house->certificate) === 'SHM'     ? 'selected' : '' }}>SHM</option>
                                    <option value="SHGB"    {{ old('certificate', $house->certificate) === 'SHGB'    ? 'selected' : '' }}>SHGB</option>
                                    <option value="Patches" {{ old('certificate', $house->certificate) === 'Patches' ? 'selected' : '' }}>Patches</option>
                                </select>
                                @error('certificate')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Deskripsi <span class="text-[#FF3E3E]">*</span></label>
                            <textarea name="about" rows="4"
                                class="{{ $inputBase }} resize-none {{ $errors->has('about') ? $inputErr : $inputOk }}">{{ old('about', $house->about) }}</textarea>
                            @error('about')
                                <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            {{-- Kamar Tidur --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kamar Tidur <span class="text-[#FF3E3E]">*</span></label>
                                <input type="number" name="bedroom" value="{{ old('bedroom', $house->bedroom) }}" min="0"
                                    class="{{ $inputBase }} {{ $errors->has('bedroom') ? $inputErr : $inputOk }}" />
                                @error('bedroom')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kamar Mandi --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kamar Mandi <span class="text-[#FF3E3E]">*</span></label>
                                <input type="number" name="bathroom" value="{{ old('bathroom', $house->bathroom) }}" min="0"
                                    class="{{ $inputBase }} {{ $errors->has('bathroom') ? $inputErr : $inputOk }}" />
                                @error('bathroom')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Luas Tanah --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Luas Tanah <span class="text-[#FF3E3E]">*</span></label>
                                <input type="number" name="land_area" value="{{ old('land_area', $house->land_area) }}" min="0"
                                    class="{{ $inputBase }} {{ $errors->has('land_area') ? $inputErr : $inputOk }}" />
                                @error('land_area')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Luas Bangunan --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Luas Bangunan <span class="text-[#FF3E3E]">*</span></label>
                                <input type="number" name="building_area" value="{{ old('building_area', $house->building_area) }}" min="0"
                                    class="{{ $inputBase }} {{ $errors->has('building_area') ? $inputErr : $inputOk }}" />
                                @error('building_area')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Daya Listrik --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Daya Listrik (W) <span class="text-[#FF3E3E]">*</span></label>
                                <input type="number" name="electric" value="{{ old('electric', $house->electric) }}" min="0"
                                    class="{{ $inputBase }} {{ $errors->has('electric') ? $inputErr : $inputOk }}" />
                                @error('electric')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Status Ketersediaan --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Status <span class="text-[#FF3E3E]">*</span></label>
                                <select name="is_available"
                                    class="{{ $inputBase }} bg-white {{ $errors->has('is_available') ? $inputErr : $inputOk }}">
                                    <option value="1" {{ old('is_available', $house->is_available ? '1' : '0') == '1' ? 'selected' : '' }}>Available</option>
                                    <option value="0" {{ old('is_available', $house->is_available ? '1' : '0') == '0' ? 'selected' : '' }}>Not Available</option>
                                </select>
                                @error('is_available')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kategori <span class="text-[#FF3E3E]">*</span></label>
                                <select name="category_id"
                                    class="{{ $inputBase }} bg-white {{ $errors->has('category_id') ? $inputErr : $inputOk }}">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $house->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kota --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Kota <span class="text-[#FF3E3E]">*</span></label>
                                <select name="city_id"
                                    class="{{ $inputBase }} bg-white {{ $errors->has('city_id') ? $inputErr : $inputOk }}">
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id', $house->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <p class="text-xs text-[#FF3E3E] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h2 class="font-bold text-[#060922] mb-4">Fasilitas</h2>
                    @php $existingFacilities = $house->facilities->pluck('facility_id')->toArray(); @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($facilities as $facility)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-[#F2F2F4] cursor-pointer hover:border-[#3F52FF]/30 has-[:checked]:border-[#3F52FF] has-[:checked]:bg-[#3F52FF]/5 transition-all">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                    class="w-4 h-4 rounded border-gray-300 text-[#3F52FF] focus:ring-[#3F52FF]"
                                    {{ in_array($facility->id, old('facilities', $existingFacilities)) ? 'checked' : '' }}>
                                <span class="text-sm text-[#060922]">{{ $facility->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Thumbnail --}}
                <div class="bg-white rounded-2xl p-5 border {{ $errors->has('thumbnail') ? 'border-[#FF3E3E]' : 'border-[#F2F2F4]' }}">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-[#060922]">Thumbnail <span class="text-[#FF3E3E]">*</span></h2>
                        @if($house->thumbnail)
                            <button type="button" id="removeThumbnailBtn" onclick="removeThumbnail()"
                                class="text-xs font-semibold text-[#FF3E3E] hover:underline">Hapus</button>
                        @endif
                    </div>
                    <input type="hidden" name="remove_thumbnail" id="removeThumbnailInput" value="0" />
                    <div id="thumbnailPreview"
                        class="relative w-full h-40 rounded-xl border-2 border-dashed {{ $errors->has('thumbnail') ? 'border-[#FF3E3E]/50' : 'border-[#F2F2F4]' }} flex items-center justify-center cursor-pointer hover:border-[#3F52FF]/30 transition-colors overflow-hidden"
                        onclick="document.getElementById('thumbnailInput').click()">
                        @if($house->thumbnail)
                            <img id="thumbnailExistingImg" src="{{ Storage::url($house->thumbnail) }}" class="absolute inset-0 w-full h-full object-cover rounded-xl" />
                            <div id="thumbnailPlaceholder" class="text-center hidden">
                        @else
                            <div id="thumbnailPlaceholder" class="text-center">
                        @endif
                            <svg class="w-10 h-10 text-[#8F91A2]/30 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-[#8F91A2]">Klik untuk upload</p>
                        </div>
                    </div>
                    <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" class="hidden"
                        onchange="previewImage(this, 'thumbnailPreview', 'thumbnailPlaceholder')" />
                    @error('thumbnail')
                        <p class="text-xs text-[#FF3E3E] mt-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                    {{-- Client-side warning (shown before submit) --}}
                    <p id="thumbnailWarning" class="text-xs text-[#FF3E3E] mt-2 hidden flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        Thumbnail telah dihapus. Upload gambar pengganti sebelum menyimpan.
                    </p>
                </div>

                {{-- Foto Existing --}}
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h2 class="font-bold text-[#060922] mb-4">Foto Existing</h2>
                    @if($house->photos->count() > 0)
                        <div class="grid grid-cols-2 gap-2 mb-3" id="existingPhotosGrid">
                            @foreach($house->photos as $photo)
                                <div class="relative w-full h-24 rounded-xl overflow-hidden group" id="photo-wrap-{{ $photo->id }}">
                                    <img src="{{ Storage::url($photo->photo) }}" class="w-full h-full object-cover" />
                                    <button type="button" onclick="removePhoto({{ $photo->id }})"
                                        class="absolute top-1 right-1 w-6 h-6 bg-[#FF3E3E] rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <input type="hidden" name="delete_photos[]" id="delete-photo-{{ $photo->id }}" value="{{ $photo->id }}" disabled />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-[#8F91A2] mb-3">Belum ada foto tambahan</p>
                    @endif
                    @error('photos.*')
                        <p class="text-xs text-[#FF3E3E] mb-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 border-dashed border-[#F2F2F4] cursor-pointer hover:border-[#3F52FF]/30 transition-colors">
                        <svg class="w-5 h-5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span class="text-sm text-[#8F91A2]">Tambah Foto Baru</span>
                        <input type="file" name="photos[]" accept="image/*" multiple class="hidden" />
                    </label>
                </div>

                <button type="submit" id="submitBtn" class="w-full py-3.5 bg-[#060922] text-white font-semibold rounded-xl hover:bg-[#060922]/90 transition-colors text-sm">
                    Update Listing
                </button>
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
            document.getElementById('removeThumbnailInput').value = '0';
            document.getElementById('thumbnailWarning').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeThumbnail() {
    document.getElementById('removeThumbnailInput').value = '1';
    const existingImg = document.getElementById('thumbnailExistingImg');
    if (existingImg) existingImg.remove();
    const newImg = document.querySelector('#thumbnailPreview img');
    if (newImg) newImg.remove();
    document.getElementById('thumbnailInput').value = '';
    const placeholder = document.getElementById('thumbnailPlaceholder');
    placeholder.style.display = '';
    placeholder.classList.remove('hidden');
    const btn = document.getElementById('removeThumbnailBtn');
    if (btn) btn.style.display = 'none';
    document.getElementById('thumbnailWarning').classList.remove('hidden');
}

function removePhoto(id) {
    document.getElementById('photo-wrap-' + id).style.display = 'none';
    document.getElementById('delete-photo-' + id).disabled = false;
}

document.getElementById('editListingForm').addEventListener('submit', function(e) {
    const removeThumbnail = document.getElementById('removeThumbnailInput').value;
    const hasNewFile = document.getElementById('thumbnailInput').files.length > 0;

    if (removeThumbnail === '1' && !hasNewFile) {
        e.preventDefault();
        document.getElementById('thumbnailWarning').classList.remove('hidden');
        document.getElementById('thumbnailWarning').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endpush
