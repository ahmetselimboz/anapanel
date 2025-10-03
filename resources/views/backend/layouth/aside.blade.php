

<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
              
                    <ul class="sidebar-menu" data-widget="tree">
                        <li>
                        <a href="{{ route('panels.index') }}">
                            <i class="fa fa-sliders me-1 fs-18"></i>
                            <span>Paneller</span>
                        </a>
                    </li>  
                        <li><a href="{{ route('plugin.index') }}"> <i class="fa fa-puzzle-piece me-1 fs-18"></i> <span>Plugin Yönetimi</span>
                            </a></li>

                            @if(isset($pluginMenus) && count($pluginMenus) > 0)
                            <li class="treeview">
                                <a href="#"><i class="fa fa-plug"></i><span>Aktif Pluginler</span><span
                                        class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span></a>
                                <ul class="treeview-menu">
                                    @foreach($pluginMenus as $pluginItem)
                                        @if($pluginItem['type'] === 'plugin')
                                            <li class="treeview">
                                                <a href="#"><i class="{{ $pluginItem['icon'] }}"></i><span>{{ $pluginItem['title'] }}</span><span
                                                        class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span></a>
                                                <ul class="treeview-menu">
                                                    @foreach($pluginItem['submenu'] as $submenuItem)
                                                        <li>
                                                            <a href="{{ route($submenuItem['route']) }}">
                                                                <i class="{{ $submenuItem['icon'] }}"></i>
                                                                <span>{{ $submenuItem['title'] }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                          <li><a href="{{ route('notification.index') }}"> <i class="fa fa-envelope me-1 fs-18"></i> <span>Bildirimler</span>
                            </a></li>
                          <li><a href="{{ route('information') }}"> <i class="fa fa-info me-1 fs-18"></i> <span>Bilgilendirme</span>
                            </a></li>
                        <!--<li><a href="{{ route('settings') }}"> <i class="fa fa-cog me-1 fs-18"></i> <span>Ayarlar</span>-->
                        <!--    </a></li>-->
                    </ul>
 
               
            </div>
        </div>
    </section>
</aside>