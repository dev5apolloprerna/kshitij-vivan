<?php 
$role = auth()->user()->role_id;

$loginUser = auth()->user();
$userId=auth()->user()->id;
$user_role_id=session()->get('user_role_id');
?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu"></span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('home')) {{ 'active' }} @endif"
                        href="{{ route('home') }}">
                        <i class="mdi mdi-speedometer"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                    aria-expanded="true" aria-controls="sidebarMore">
                    <i class="fa fa-list text-white"></i>Master Entry </a>
                    <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('employee.index')) {{ 'active' }} @endif"
                                    href="{{ route('employee.index') }}">
                                    <i class="fa-regular fa-user"></i>
                                    <span data-key="t-dashboards">Employee</span>
                                </a>
                            </li> 
                           </ul>
                    </div>
                </li>
               <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="collapse" role="button"
                        aria-expanded="true" aria-controls="sidebarMore">
                        <i class="fa fa-list"></i> Report</a>
                    <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                        <ul class="nav nav-sm flex-column">
                             <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('report.today')) {{ 'active' }} @endif"
                                    href="{{ route('report.today') }}">
                                    <i class="fa-solid far fa-calendar-check"></i>
                                    <span data-key="t-dashboards">Today Report</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('report.monthly')) {{ 'active' }} @endif"
                                    href="{{ route('report.monthly') }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span data-key="t-dashboards">Monthly Report</span>
                                </a>
                            </li>                            
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('attendance.index')) {{ 'active' }} @endif"
                        href="{{ route('attendance.index') }}">
                        <i class="fa-solid fa-calendar-alt"></i>
                        <span data-key="t-dashboards">Attendance</span>
                    </a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('Inquiry.index')) {{ 'active' }} @endif"
                        href="{{ route('Inquiry.index') }}">
                        <i class="fa-solid fa-circle-question"></i>
                        <span data-key="t-dashboards">Inquiry</span>
                    </a>
                </li>
               
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>