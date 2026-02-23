<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo / User Info -->
    <div class="dropdown">
        <a href="javascript:void(0)" class="brand-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <?php if(empty($_SESSION['login_avatar'])): ?>
                <span class="brand-image img-circle elevation-3 d-flex justify-content-center align-items-center bg-primary text-white font-weight-bold" 
                      style="width: 38px; height: 38px; font-size: 1.2rem;">
                    <?php echo strtoupper(substr($_SESSION['login_firstname'], 0, 1) . substr($_SESSION['login_lastname'], 0, 1)); ?>
                </span>
            <?php else: ?>
                <img src="../assets/uploads/<?php echo $_SESSION['login_avatar']; ?>" 
                     class="brand-image img-circle elevation-3" 
                     style="width: 38px; height: 38px; object-fit: cover;" 
                     alt="User Image">
            <?php endif; ?>
            <span class="brand-text font-weight-light"><?php echo ucwords($_SESSION['login_firstname'] . ' ' . $_SESSION['login_lastname']); ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item manage_account" href="javascript:void(0)" data-id="<?php echo $_SESSION['login_id']; ?>">
                <i class="fas fa-user-cog mr-2"></i>Manage Account
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="ajax.php?action=logout">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="./" class="nav-link nav-home">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                                  <!-- Departments (example additional menu) -->
                <li class="nav-item">
                      <a href="./index.php?page=student_list" class="nav-link nav-student_list">
                          <i class="far fa-circle nev-icon"></i>
                          <p>Departments</p>
                      </a>
                 </li>
                 
                <!-- Results -->
                <li class="nav-item">
                    <a href="./index.php?page=results" class="nav-link nav-results">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Results</p>
                    </a>
                </li> 
                <!-- Classes -->
                <li class="nav-item">
                    <a href="./index.php?page=classes" class="nav-link nav-classes">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Classes</p>
                    </a>
                </li>

                <!-- Subjects -->
<li class="nav-item">
    <a href="./index.php?page=subjects" class="nav-link nav-subjects">
        <i class="nav-icon fas fa-book-open"></i>
        <p>Subjects</p>
    </a>
</li>

<li class="nav-item">
    <a href="./index.php?page=new_student" class="nav-link nav-new_student">
        <i class="nav-icon fas fa-user-plus"></i>
        <p>Add New Student</p>
    </a>
</li>

            
        </nav>
    </div>
</aside>

<script>
$(document).ready(function(){
    // Get current page from URL (fallback to 'home')
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home'; ?>';

    // Remove any existing active classes
    $('.nav-link').removeClass('active');
    $('.nav-item').removeClass('menu-open');

    // Try to find the nav-link with class 'nav-'+page
    var $activeLink = $('.nav-link.nav-' + page);
    
    if ($activeLink.length) {
        // Add active class to the link itself
        $activeLink.addClass('active');
        
        // If this link is inside a treeview (submenu), we need to open its parent
        var $parentLi = $activeLink.closest('.nav-treeview').closest('.nav-item');
        if ($parentLi.length) {
            $parentLi.addClass('menu-open');
            $parentLi.children('.nav-link').addClass('active');
        }
    } else {
        // Fallback: if no match, maybe highlight the parent based on page prefix
        // Example: for 'new_student' and 'student_list', highlight the Students parent
        if (page.startsWith('new_student') || page.startsWith('student_list')) {
            $('.nav-link.nav-edit_student').closest('.nav-item').addClass('menu-open');
            $('.nav-link.nav-edit_student').addClass('active');
        }
        // Add more fallbacks as needed
    }

    // Manage account modal trigger
    $('.manage_account').click(function(e){
        e.preventDefault();
        uni_modal('Manage Account', 'manage_user.php?id=' + $(this).data('id'));
    });

    // Optional: tooltips or other enhancements
});
</script>