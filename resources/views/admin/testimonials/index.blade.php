@extends('layouts.admin')
@section('title', 'Testimonials | Admin Panel')
@section('page-title', 'Testimonials')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.testimonials.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-4 py-2 rounded inline-flex items-center">
        <i class="fas fa-plus mr-2"></i> Add Testimonial
    </a>
</div>

<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-800 border-b border-white/5">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Testimonial</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Rating</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Order</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($testimonials as $testimonial)
            <tr class="hover:bg-dark-800/50">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        @if($testimonial->customer_image)
                        <img src="{{ asset('storage/'.$testimonial->customer_image) }}" class="w-10 h-10 rounded-full mr-3" alt="">
                        @else
                        <div class="w-10 h-10 rounded-full bg-gold-500/20 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-gold-500"></i>
                        </div>
                        @endif
                        <div>
                            <div class="text-white font-medium">{{ $testimonial->customer_name }}</div>
                            <div class="text-gray-500 text-sm">{{ $testimonial->customer_title }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-400">{{ Str::limit($testimonial->testimonial, 60) }}</td>
                <td class="px-6 py-4">
                    <div class="flex text-gold-500">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $testimonial->rating ? '' : ' opacity-20' }}"></i>
                        @endfor
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded {{ $testimonial->is_active ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                        {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-400">{{ $testimonial->display_order }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="text-blue-400 hover:text-blue-300 mr-3"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this testimonial?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No testimonials yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
