<x-home.layout>
    {{-- This for the variables --}}
    <x-slot:nickname>{{ $user->nickname }}</x-slot>

    {{-- This is the main content --}}
    <h1 class="mt-4">Content Title</h1>
    <p>This is where your main content goes. You can use Bootstrap's typography classes to style your text.</p>
    <h2>Subheading</h2>
    <p>More content here. You can add images, lists, and other elements to make your content engaging.</p>
    <img src="images/kaveh-picture.jpg" class="img-fluid rounded" alt="Responsive image">
</x-home.layout>
