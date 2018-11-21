<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        {{--<div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>--}}
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>

            @can('super-admin', Auth::user())

               <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Admin</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('admin.index') }}"><i class="fa fa-circle-o"></i> Admins</a></li>
                        <li><a href="{{ route('role.index') }}"><i class="fa fa-circle-o"></i> Roles</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Place</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('place.index') }}"><i class="fa fa-circle-o"></i> Places</a></li>
                        <li><a href="{{ route('category.index') }}"><i class="fa fa-circle-o"></i> Place Categories</a></li>
                        <li><a href="{{ route('tag.index') }}"><i class="fa fa-circle-o"></i> Place Tags</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Hotel</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('hotel.index') }}"><i class="fa fa-circle-o"></i> Hotels</a></li>
                        <li><a href="{{ route('type.index') }}"><i class="fa fa-circle-o"></i> Hotel Types</a></li>
                        <li><a href="{{ route('room.index') }}"><i class="fa fa-circle-o"></i> Room Types</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Article</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('article.index') }}"><i class="fa fa-circle-o"></i> Articles</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Tour Package</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('tour-package.index') }}"><i class="fa fa-circle-o"></i> Tour Packages</a></li>
                        <li><a href="{{ route('category.index') }}"><i class="fa fa-circle-o"></i> Categories</a></li>
                        <li><a href="{{ route('tag.index') }}"><i class="fa fa-circle-o"></i> Tags</a></li>
                    </ul>
                </li>

            @endcan

            @can('place-admin', Auth::user())

                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Place</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('place.index') }}"><i class="fa fa-circle-o"></i> Places</a></li>
                        <li><a href="{{ route('category.index') }}"><i class="fa fa-circle-o"></i> Place Categories</a></li>
                        <li><a href="{{ route('tag.index') }}"><i class="fa fa-circle-o"></i> Place Tags</a></li>
                    </ul>
                </li>

            @endcan

            @can('hotel-admin', Auth::user())

                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Hotel</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('hotel.index') }}"><i class="fa fa-circle-o"></i> Hotels</a></li>
                        <li><a href="{{ route('type.index') }}"><i class="fa fa-circle-o"></i> Hotel Types</a></li>
                        <li><a href="{{ route('room.index') }}"><i class="fa fa-circle-o"></i> Room Types</a></li>
                    </ul>
                </li>

            @endcan

            @can('article-admin', Auth::user())

                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Article</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('article.index') }}"><i class="fa fa-circle-o"></i> Articles</a></li>
                        <li><a href="{{ route('category.index') }}"><i class="fa fa-circle-o"></i> Categories</a></li>
                        <li><a href="{{ route('tag.index') }}"><i class="fa fa-circle-o"></i> Tags</a></li>
                    </ul>
                </li>

            @endcan

            @can('travel-agent', Auth::user())

                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Tour Package</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('tour-package.index') }}"><i class="fa fa-circle-o"></i> Tour Packages</a></li>
                        <li><a href="{{ route('category.index') }}"><i class="fa fa-circle-o"></i> Categories</a></li>
                        <li><a href="{{ route('tag.index') }}"><i class="fa fa-circle-o"></i> Tags</a></li>
                    </ul>
                </li>

            @endcan

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>