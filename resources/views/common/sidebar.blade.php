<?php 
if(auth()->user())
{
$roleid = auth()->user()->role_id;
}else{

$roleid = Auth::guard('web_employees')->user()->role_id;
}

?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu"></span></li>
                @if($roleid == '1' && $roleid != 2)
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
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('holiday.index')) {{ 'active' }} @endif"
                                    href="{{ route('holiday.index') }}">
                                    <i class="fa-regular fa-calendar"></i>
                                    <span data-key="t-dashboards"> Holiday Master </span>
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
            <li class="nav-item">
                <a class="nav-link menu-link @if (request()->routeIs('salary_process.index')) {{ 'active' }} @endif"
                    href="{{ route('salary_process.index') }}">
                    <i class="fa-solid fa-inr"></i>
                    <span data-key="t-dashboards">Process Salary</span>
                </a>
            </li>
             <li class="nav-item">
                <a class="nav-link menu-link @if (request()->routeIs('salary_processed.index')) {{ 'active' }} @endif"
                    href="{{ route('salary_processed.index') }}">
                    <i class="fa-solid fa-inr"></i>
                    <span data-key="t-dashboards">Processed Salary</span>
                </a>
            </li>
             @endif
            @if(($roleid == '2' && $roleid != 1) || ($roleid == '3' && $roleid != 1))
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('userhome')) {{ 'active' }} @endif"
                        href="{{ route('userhome') }}">
                        <i class="mdi mdi-speedometer"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('empreport.monthly')) {{ 'active' }} @endif"
                        href="{{ route('empreport.monthly') }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span data-key="t-dashboards">Monthly Report</span>
                    </a>
                </li>   
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('empattendance.index')) {{ 'active' }} @endif"
                        href="{{ route('empattendance.index') }}">
                        <i class="fa-solid fa-calendar-alt"></i>
                        <span data-key="t-dashboards">Attendance</span>
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('empreport.empSalary')) {{ 'active' }} @endif"
                        href="{{ route('empreport.empSalary') }}">
                        <i class="fa fa-inr"></i>
                        <span data-key="t-dashboards">Salary</span>
                    </a>
                </li>
                @endif


        </ul>
    </div>
    <!-- Sidebar -->
</div>

<div class="sidebar-background"></div>
</div>