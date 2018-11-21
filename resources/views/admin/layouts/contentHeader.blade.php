<section class="content-header">
    @if (url()->current() == route('admin.home'))
    <h1>
        Home
    </h1>
    <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    </ol>
    @elseif (url()->current() == route('admin.index'))
        <h1>
            Admins
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Admin</a></li>
        </ol>
    @elseif (url()->current() == route('admin.create'))
        <h1>
            Admin Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li><a href="{{ route('admin.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/admin") && substr(url()->current(), -4) == "edit")
        <h1>
            Admin Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>
    @elseif (url()->current() == route('role.index'))
        <h1>
            Admin Roles
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('role.index') }}"><i class="fa fa-dashboard"></i> Admin Role</a></li>
        </ol>
    @elseif (url()->current() == route('role.create'))
        <h1>
            Admin Role Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('role.index') }}"><i class="fa fa-dashboard"></i> Admin Role</a></li>
            <li><a href="{{ route('role.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/role") && substr(url()->current(), -4) == "edit")
        <h1>
            Admin Role Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('role.index') }}"><i class="fa fa-dashboard"></i> Admin Role</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>
    @elseif (url()->current() == route('place.index'))
        <h1>
            Places
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('place.index') }}"><i class="fa fa-dashboard"></i> Place</a></li>
        </ol>
    @elseif (url()->current() == route('place.create'))
        <h1>
            Place Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('place.index') }}"><i class="fa fa-dashboard"></i> Place</a></li>
            <li><a href="{{ route('place.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/place") && substr(url()->current(), -4) == "edit")
        <h1>
            Place Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('place.index') }}"><i class="fa fa-dashboard"></i> Place</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>
    @elseif (url()->current() == route('category.index'))
        <h1>
            Place Categories
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('category.index') }}"><i class="fa fa-dashboard"></i> Place Category</a></li>
        </ol>
    @elseif (url()->current() == route('category.create'))
        <h1>
            Place Category Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('category.index') }}"><i class="fa fa-dashboard"></i> Place Category</a></li>
            <li><a href="{{ route('category.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/category") && substr(url()->current(), -4) == "edit")
        <h1>
            Place Category Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('category.index') }}"><i class="fa fa-dashboard"></i> Place Category</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>
    @elseif (url()->current() == route('tag.index'))
        <h1>
            Place Tags
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('tag.index') }}"><i class="fa fa-dashboard"></i> Place Tag</a></li>
        </ol>
    @elseif (url()->current() == route('tag.create'))
        <h1>
            Place Tag Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('tag.index') }}"><i class="fa fa-dashboard"></i> Place Tag</a></li>
            <li><a href="{{ route('tag.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/tag") && substr(url()->current(), -4) == "edit")
        <h1>
            Place Tag Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('tag.index') }}"><i class="fa fa-dashboard"></i> Place Tag</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>
    @elseif (url()->current() == route('hotel.index'))
        <h1>
            Hotels
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('hotel.index') }}"><i class="fa fa-dashboard"></i> Hotel</a></li>
        </ol>
    @elseif (url()->current() == route('hotel.create'))
        <h1>
            Hotel Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('hotel.index') }}"><i class="fa fa-dashboard"></i> Hotel</a></li>
            <li><a href="{{ route('hotel.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/hotel") && substr(url()->current(), -4) == "edit")
        <h1>
            Hotel Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('hotel.index') }}"><i class="fa fa-dashboard"></i> Hotel</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>
    @elseif (url()->current() == route('type.index'))
        <h1>
            Hotel Types
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('type.index') }}"><i class="fa fa-dashboard"></i> Hotel Type</a></li>
        </ol>
    @elseif (url()->current() == route('type.create'))
        <h1>
            Hotel Type Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('type.index') }}"><i class="fa fa-dashboard"></i> Hotel Type</a></li>
            <li><a href="{{ route('type.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/type") && substr(url()->current(), -4) == "edit")
        <h1>
            Hotel Type Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('type.index') }}"><i class="fa fa-dashboard"></i> Hotel Type</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>
    @elseif (url()->current() == route('room.index'))
        <h1>
            Hotel Room Types
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('room.index') }}"><i class="fa fa-dashboard"></i> Hotel Room</a></li>
        </ol>
    @elseif (url()->current() == route('room.create'))
        <h1>
            Hotel Room Type Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('room.index') }}"><i class="fa fa-dashboard"></i> Hotel Room Type</a></li>
            <li><a href="{{ route('room.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/room") && substr(url()->current(), -4) == "edit")
        <h1>
            Hotel Room Type Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('room.index') }}"><i class="fa fa-dashboard"></i> Hotel Room Type</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>
    @elseif (url()->current() == route('article.index'))
        <h1>
            Articles
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('article.index') }}"><i class="fa fa-dashboard"></i> Article</a></li>
        </ol>
    @elseif (url()->current() == route('article.create'))
        <h1>
            Article Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('article.index') }}"><i class="fa fa-dashboard"></i> Article</a></li>
            <li><a href="{{ route('article.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/article") && substr(url()->current(), -4) == "edit")
        <h1>
            Article Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('article.index') }}"><i class="fa fa-dashboard"></i> Article</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>
    @elseif (url()->current() == route('tour-package.index'))
        <h1>
            Tour Packages
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('tour-package.index') }}"><i class="fa fa-dashboard"></i> Tour Package</a></li>
        </ol>
    @elseif (url()->current() == route('tour-package.create'))
        <h1>
            Tour Package Create
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('tour-package.index') }}"><i class="fa fa-dashboard"></i> Tour Package</a></li>
            <li><a href="{{ route('tour-package.create') }}"><i class="fa fa-dashboard"></i> Create</a></li>
        </ol>
    @elseif (strpos(url()->current(), "admin/tour-package") && substr(url()->current(), -4) == "edit")
        <h1>
            Tour Package Edit
        </h1>
        <ol class="breadcrumb">

            <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('tour-package.index') }}"><i class="fa fa-dashboard"></i> Tour Package</a></li>
            <li><a href="{{ url()->current() }}"><i class="fa fa-dashboard"></i> Edit</a></li>
        </ol>

    @endif




</section>
