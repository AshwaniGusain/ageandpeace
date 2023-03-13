@include('admin::layouts.partials.header')

	<div id="snap-topbar" class="bg-darker">
		<header>
			<button id="snap-mobile-menu" class="btn" @click="showMenu()" :disabled="previewMode"><i class="fa fa-list"></i></button>
		</header>
	</div>

	<div class="row">

		<div id="snap-sidebar" class="bg-dark snap-sidebar" ref="sidebar">
			<div>

				<header id="snap-menu-header" class="align-bottom bg-darker shadow">
					<div>
						<a href="{{ url('') }}" class="text-truncate col" id="snap-mobile-link-external" target="_blank" title="{{ $admin_title }}">
							<span>{{ $admin_title }}</span>
							<i class="fa fa-external-link-square"></i>
						</a>

						<button id="snap-close-menu" class="btn" @click="hideMenu()"><i class="fa fa-caret-square-o-left"></i></button>

					</div>

				</header>

				<nav id="snap-menu" class="snap-sidebar-nav" role="navigation" ref="menu">
					@if (\Admin::modules()->has('search') && Auth::user()->can(Admin::modules()->get('search')->defaultPermission()))
						<form id="snap-form-search" class="form" action="{{ \Admin::modules()->get('search')->url() }}" method="get">
							<div class="row mb-3">
								<div class="col">
									<div class="form-inline">
										<div role="group" class="input-group">
											<input type="text" name="q" id="snap-menu-search" value="" placeholder="Search" aria-label="Search" class="bg-darker form-control">
											<div class="input-group-append">
												<button type="submit" class="btn btn-secondary bg-darker">
													<i class="fa fa-search bg-darker" style="color: #777;"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					@endif
					{{ $menu }}


				</nav>



				<footer class="bg-darker">

					<div class="row">
						<div class="col-3">
							<a href="<?=admin_url('me')?>" class="btn btn-sm btn-secondary" style="background-color: #495057; border-color: #777;">
								<i class="fa fa-user-circle" style="color: #adb5bd;"></i>
							</a>
						</div>

						<div class="col-6">
							<div style="line-height: 14px;"><a href="<?=admin_url('me')?>"><small>{{ Auth::user()->name }}</small></a></div>
                            {{--<div><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" id="snap-logout"><small>Logout</small></a></div>--}}
						</div>
						<div class="col-2" style="border-left: 1px solid #777;">
							<a href="{{ route('admin/logout') }}" class="snap-logout" id="snap-logout">
								<i class="fa fa-power-off text-center" style="color: #777; width: 24px; margin-top: 7px"></i>
							</a>
						</div>

						<?php /* ?><snap-loader ref="loader"></snap-loader><?php */ ?>

					</div>
				</footer>
			</div>
		</div>

		<main id="snap-main" role="main">
			@yield('body')
		</main>
		{{--<snap-resource-preview url="{{ url('') }}" id="snap-resource-preview" ref="snap-resource-preview"></snap-resource-preview>--}}

	</div>



@include('admin::layouts.partials.footer')