@php $product ??= null; @endphp

<div>
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product?->name)" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div>
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300">{{ old('description', $product?->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div>
    <x-input-label for="price_cents" :value="__('Price (cents)')" />
    <x-text-input id="price_cents" name="price_cents" type="number" min="0" class="mt-1 block w-full" :value="old('price_cents', $product?->price_cents)" required />
    <x-input-error :messages="$errors->get('price_cents')" class="mt-2" />
</div>

<div>
    <x-input-label for="stock" :value="__('Stock')" />
    <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full" :value="old('stock', $product?->stock)" required />
    <x-input-error :messages="$errors->get('stock')" class="mt-2" />
</div>

<div>
    <x-input-label for="status" :value="__('Status')" />
    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300">
        <option value="active" @selected(old('status', $product?->status) === 'active')>{{ __('Active') }}</option>
        <option value="inactive" @selected(old('status', $product?->status) === 'inactive')>{{ __('Inactive') }}</option>
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
</div>

<div>
    <x-input-label for="image" :value="__('Image')" />
    <input id="image" name="image" type="file" class="mt-1 block w-full text-sm" />
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
</div>
