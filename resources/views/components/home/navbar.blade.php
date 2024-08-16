<nav class="navbar navbar-light bg-light sticky-top">
    <div class="container-fluid">
      <button class="btn btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
        <i class="fas fa-bars-staggered fa-2x"></i>
      </button>
      <div class="dropdown">
        <button class="btn navbar-brand" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" style="outline: none;">
          <img src="images/kaveh-picture.jpg" alt="Profile" width="50" height="50" class="rounded-circle border-2 border-black img-hover" style="--bs-border-opacity: .3;" id="profilePicture">
          <span class="ms-2">{{ $nickname }}</span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a class="dropdown-item" href="/logout">Logout</a></li>
        </ul>
      </div>
      <input type="checkbox" id="imgToggle" style="display: none;">
      <label for="imgToggle" style="display: none;"></label>

    </div>
  </nav>
