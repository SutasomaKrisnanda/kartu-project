{{-- {{ dd($items->where('type', 'card')->first()->effect) }} --}}
<x-home.layout>
    {{-- This for the variables --}}
    <x-slot:nickname>{{ $user->nickname }}</x-slot>

    {{-- This is the main content --}}
    <h1 class="mt-4">Inventory</h1>
    <p>Welcome to Inventory Menu</p>

    <body>
        <!-- Category Tabs -->
        <div class="container">
            <!-- Category Tabs -->
            <ul class="nav nav-tabs category-tabs mb-1">
                <li class="nav-item">
                    <button class="nav-link active" onclick="filterCategory('card')">Card</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" onclick="filterCategory('item')">Items</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" onclick="filterCategory('material')">Material</button>
                </li>
            </ul>
            <!-- Sorting Buttons -->
            <div class="d-flex sorting-buttons mb-3">
                <button class="btn btn-outline-secondary" onclick="sortItems('alphabetical')">Alphabetical</button>
                <button class="btn btn-outline-secondary" onclick="sortItems('date')">Date Added</button>
                <button class="btn btn-outline-secondary" onclick="sortItems('rarity')">Rarity/Value</button>
                <button class="btn btn-outline-secondary" onclick="sortItems('quantity')">Quantity</button>
            </div>
            <!-- Inventory Container -->
            <div class="inventory-container border rounded p-3 bg-white d-flex flex-wrap gap-3">
                <!-- Example Item -->
                @foreach ($items as $item)
                <div class="card category-{{ $item->type }}" style="width: 10rem;">
                    <img src="{{ $item->image }}" class="card-img-top" alt="{{ $item->token }}"
                        style="width: 100%; cursor: pointer;"
                        onclick="showItemDetails(
                        '{{ $item->image }}',
                        '{{ $item->name }}',
                        '{{ $item->description }}',
                        '{{ $item->pivot->quantity }}',
                        '{{ $item->type === 'card' ? $item->effect->type : '' }}',
                        '{{ $item->type === 'card' ? $item->effect->value : '' }}'
                    )">
                    <div class="card-body">
                        <span class="rounded-circle bg-warning text-white text-center"
                            style="font-size: 15px; width: 20px; height: 20px; display: inline-block; margin-right: 10px; user-select: none;">
                            {{ $item->pivot->quantity }}
                        </span>
                        <b class="card-title d-flex align-items-center" style="cursor: pointer;"
                            onclick="showItemDetails(
                            '{{ $item->image }}',
                            '{{ $item->name }}',
                            '{{ $item->description }}',
                            '{{ $item->pivot->quantity }}',
                            '{{ $item->type === 'card' ? $item->effect->type : '' }}',
                            '{{ $item->type === 'card' ? $item->effect->value : '' }}'
                        )">
                            {{ $item->name }}
                        </b>
                    </div>
                    <input type="hidden" name="date" value="{{ $item->pivot->updated_at }}">
                    <input type="hidden" name="rarity" value="{{ $item->rarity }}">
                    <input type="hidden" name="description" value="{{ $item->description }}">
                </div>
                @endforeach
                <!-- Add more items here -->
            </div>
        </div>

    </body>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let currentSortMethod = null;
        let currentSortOrder = 'asc'; // Default sort order

        function filterCategory(category) {
            const items = document.querySelectorAll('.card');
            items.forEach(item => {
                if (item.classList.contains("category-" + category)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // Update active tab
            const tabs = document.querySelectorAll('.nav-link');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`.nav-link[onclick="filterCategory('${category}')"]`).classList.add('active');
        }

        function sortItems(method) {
            const container = document.querySelector('.inventory-container');
            const items = Array.from(container.querySelectorAll('.card'));

            // Determine the sort order (toggle between 'asc' and 'desc')
            if (currentSortMethod === method) {
                currentSortOrder = currentSortOrder === 'desc' ? 'asc' : 'desc';
            } else {
                currentSortOrder = 'asc'; // Reset to ascending on new sort method
            }
            currentSortMethod = method;

            // Perform the sorting based on the selected method and order
            let sortedItems;
            switch (method) {
                case 'alphabetical':
                    sortedItems = items.sort((a, b) => {
                        const nameA = a.querySelector('.card-title').textContent.trim().toUpperCase();
                        const nameB = b.querySelector('.card-title').textContent.trim().toUpperCase();
                        return nameA.localeCompare(nameB) * (currentSortOrder === 'asc' ? 1 : -1);
                    });
                    break;
                case 'date':
                    sortedItems = items.sort((a, b) => {
                        const dateA = new Date(a.querySelector('input[name="date"]').value);
                        const dateB = new Date(b.querySelector('input[name="date"]').value);
                        return (dateB - dateA) * (currentSortOrder === 'asc' ? -1 : 1);
                    });
                    break;
                case 'rarity':
                    sortedItems = items.sort((a, b) => {
                        const rarityA = parseInt(a.querySelector('input[name="rarity"]').value);
                        const rarityB = parseInt(b.querySelector('input[name="rarity"]').value);
                        return (rarityB - rarityA) * (currentSortOrder === 'asc' ? -1 : 1);
                    });
                    break;
                case 'quantity':
                    sortedItems = items.sort((a, b) => {
                        const quantityA = parseInt(a.querySelector('.rounded-circle').textContent.trim());
                        const quantityB = parseInt(b.querySelector('.rounded-circle').textContent.trim());
                        return (quantityB - quantityA) * (currentSortOrder === 'asc' ? -1 : 1);
                    });
                    break;
                default:
                    sortedItems = items;
            }

            // Clear the container and append sorted items
            container.innerHTML = '';
            sortedItems.forEach(item => container.appendChild(item));

            // Update sorting button icons
            updateSortIcons(method);
        }

        function updateSortIcons(activeMethod) {
            const buttons = document.querySelectorAll('.sorting-buttons .btn');
            buttons.forEach(button => {
                const method = button.getAttribute('onclick').match(/'(\w+)'/)[1];
                button.innerHTML = method.charAt(0).toUpperCase() + method.slice(1);

                if (method === activeMethod) {
                    const icon = currentSortOrder === 'desc' ? '▼' : '▲';
                    button.innerHTML += ` ${icon}`;
                }
            });
        }

        // Function to show item details in a popup
        function showItemDetails(image, name, description, quantity, effectType, effectValue) {
            let effectHtml = '';

            if (effectType && effectValue) {
                effectHtml = `<p><strong>Effect:</strong> ${effectValue} ${effectType}</p>`;
            }

            Swal.fire({
                title: name,
                html: `
                    <p><strong>Quantity:</strong> ${quantity}</p>
                    ${effectHtml}
                    <p><strong>Description:</strong></p>
                    <p>${description}</p>
                `,
                imageUrl: image,
                imageWidth: 300,
                imageAlt: name,
                confirmButtonText: 'Close'
            });
        }


        // Initial Setup
        document.addEventListener('DOMContentLoaded', () => {
            filterCategory('card'); // Default to 'Card' category on load
        });
    </script>
</x-home.layout>




{{-- <div class="container rounded-4" style="background: linear-gradient(45deg, #7ed957, #007e50); padding: 10px;">
        <div class="row mb-4">
            <div class="col-12">
                <div class="btn-group rounded-4" role="group" aria-label="Sort Buttons" style="padding: 10px;">
                    <span class="input-group-text" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">Sort By:</span>
                    <button type="button" class="btn btn-light text-dark" data-sort="type">Type</button>
                    <button type="button" class="btn btn-light text-dark" data-sort="quantity">Quantity</button>
                    <button type="button" class="btn btn-light text-dark" data-sort="date">Date Created</button>
                </div>

            </div>
        </div>

        <div class="row " id="card-container" style="padding: 10px">
            <div style="width: auto;" class="mb-4">
                <div class="card" style="width: 10rem;">
                    <img src="images/card/test.png" class="card-img-top img-fluid" alt="card.type" style="width: 100%; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">card.type</h5>
                        <p class="card-text">Quantity: card.quantity</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

{{--
     document.querySelectorAll('.btn-group button').forEach(button => {
             button.addEventListener('click', (button) => {

                 let sortOrder = button.getAttribute('data-sort');  Get the selected sorting option
                 console.log(sortOrder);
                 fetch('/inventory', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')  Include CSRF token
                     },
                     body: JSON.stringify({ sort: sortOrder })  Send sorting data
                 })
                 .then(response => response.json())
                 .then(data => {
                      Update the inventory list with the sorted data
                     const inventoryList = document.getElementById('inventoryList');
                     inventoryList.innerHTML = '';  Clear existing list

                     data.inventory.forEach(item => {
                         const listItem = document.createElement('li');
                         listItem.textContent = item.name;  Assuming your items have a 'name' property
                         inventoryList.appendChild(listItem);
                     });
                 });
             })
         }) --}}
