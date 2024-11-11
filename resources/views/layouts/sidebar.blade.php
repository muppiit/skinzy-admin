 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="{{ route('admin.dashboard') }}" class="brand-link">
         <span class="brand-text font-weight-light">SKINZY</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">

         <!-- Sidebar Menu -->
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                 <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                 <li class="nav-item">
                     <a href="{{ route('products.index') }}" class="nav-link">
                         <i class="nav-icon fas fa-box"></i>
                         <p>
                             Products
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                    <a href="{{ route('skinConditions.index') }}" class="nav-link">
                        <i class="nav-icon far fa-flushed"></i>
                        <p>
                            Skin Conditions
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('treatments.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Treatments
                        </p>
                    </a>
                </li>
             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
 </aside>

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <div class="content-header">
         <div class="container-fluid">
             <div class="row mb-2">
             </div><!-- /.row -->
         </div><!-- /.container-fluid -->
     </div>
     <!-- /.content-header -->
