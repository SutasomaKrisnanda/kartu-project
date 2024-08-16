
<div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
  <div class="offcanvas-header">
    <div>
      <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Menu</h5>
      <p class="text-muted">What are you gonna do today?</p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body">
    <ul class="nav nav-pills flex-column">

      {{-- If Active (text-light bg-dark) else () ---}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('home') ? 'text-light bg-dark' : '' }}" aria-current="page" href="/home" style="font-size: 1.2em; font-weight: 500;">
                <i class="fas fa-home" style="margin-right: 10px;"></i> Home
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('battle') ? 'text-light bg-dark' : '' }}" href="/battle" style="font-size: 1.2em; font-weight: 500;">
                <i class="fas fa-sword" style="margin-right: 10px;"></i> Battle
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('inventory') ? 'text-light bg-dark' : '' }}" href="/inventory" style="font-size: 1.2em; font-weight: 500;">
                <i class="fas fa-backpack" style="margin-right: 10px;"></i> Inventory
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('quest') ? 'text-light bg-dark' : '' }}" href="/quest" style="font-size: 1.2em; font-weight: 500;">
                <i class="fas fa-scroll" style="margin-right: 10px;"></i> Quest
            </a>
        </li>

    </ul>

  </div>
</div>

<style>
#offcanvasScrolling .nav-link {
  color: black;
}

#offcanvasScrolling .nav-link:hover {
  background-color: rgb(33, 37, 41, 0.5);
  color: white;
  transition: background-color 0.3s ease;
}

</style>
