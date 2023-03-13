<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row py-5 align-items-center text-center text-sm-left">
                <div class="col-12 col-sm-6 mb-1">
                    <a href="{{ url('/') }}">
                        <span class="sr-only">Age &amp; Peace</span>
                        @svg('assets/svg/logo_footer.svg', 'logo-footer')
                    </a>
                </div>

                <div class="col-12 col-sm-6 mb-1">
                    <ul class="nav justify-content-center justify-content-sm-end social-links">
                        <li class="nav-item">
                            <a href="https://www.facebook.com/Age-and-Peace-756983408020299/" target="_blank" class="nav-link">
                                @svg('assets/svg/icon_facebook_footer.svg', 'icon')
                                <span class="sr-only">Facebook</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="https://twitter.com/AgeandPeace1" target="_blank" class="nav-link">
                                @svg('assets/svg/icon_twitter_footer.svg', 'icon')
                                <span class="sr-only">Twitter</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="https://www.pinterest.com/ageandpeace/" target="_blank" class="nav-link">
                                @svg('assets/svg/icon_pinterest_footer.svg', 'icon')
                                <span class="sr-only">Pinterest</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="https://www.instagram.com/AgeandPeace/" target="_blank" class="nav-link">
                                @svg('assets/svg/icon_instagram.svg', 'icon')
                                <span class="sr-only">Instagram</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="https://cl.linkedin.com/company/age-and-peace" target="_blank" class="nav-link">
                                @svg('assets/svg/icon_linkedin_footer.svg', 'icon')
                                <span class="sr-only">LinkedIn</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row pt-7 text-center text-sm-left">
                <div class="col-12 col-sm-6 col-md-3">
                    <ul class="nav footer-pages-nav flex-column">

                        <li class="nav-item">
                            <a href="{{route('pages', ['checklist'])}}" class="nav-link">Age and Peace Checklist</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('providers')}}" class="nav-link">Find a Provider</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('pages', ['providers/become-a-provider'])}}" class="nav-link">Become a Provider</a>
                        </li>

                    </ul>
                </div>

                <div class="col-12 col-sm-6 col-md-3 mb-7">
                    <ul class="nav footer-pages-nav flex-column">

                        <li class="nav-item">
                            <a href="{{route('pages', ['about'])}}" class="nav-link">About</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('news')}}" class="nav-link">News &amp; Articles</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('contact')}}" class="nav-link">Contact Us</a>
                        </li>

                    </ul>
                </div>

                <div class="col-12 col-md-6">
                    <div id="mc_embed_signup">
                        <form action="https://heritageup.us20.list-manage.com/subscribe/post-json?u=43c54ada7e366e83d7314926d&amp;id=fbc6b42983&c=?"
                              method="get" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                              class="validate" target="_blank" novalidate>
                            <div class="form-group newsletter">
                                <label for="mce-EMAIL" class="text-white">Newsletter Signup</label>

                                <div id="subscribe-result" class="clear"></div>

                                <div class="input-group">
                                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                    <div class="sr-only" aria-hidden="true">
                                        <input type="text" name="b_43c54ada7e366e83d7314926d_fbc6b42983" tabindex="-1" value="">
                                    </div>
                                    <input type="email" value="" name="EMAIL" placeholder="Enter your email address" class="form-control border-0 required email" id="mce-EMAIL">

                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit" id="mc-embedded-subscribe"
                                                aria-label="Subscribe">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.1 12.3"
                                                 height="16px">
                                                <path fill="currentColor"
                                                      d="M14.3 5.2l-.8-.8L9.2.3C9 .1 8.6.1 8.4.3l-.6.5c-.2.2-.2.6 0 .8l3.1 3c.2.2.2.4-.2.4H.6c-.3.1-.6.3-.6.6v1c0 .3.3.6.6.6h10.2c.3 0 .4.2.2.4l-3.1 3c-.2.2-.2.6 0 .8l.5.6c.2.2.6.2.8 0l4.2-4.1.8-.8.6-.6c.2-.2.2-.6 0-.8l-.5-.5z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="colophon text-center text-sm-left pb-3 pt-5">
            <div class="container">
                <div class="row legal">
                    <div class="col">
                        <ul class="nav footer-legal-nav justify-content-center justify-content-sm-start">
                            <li class="nav-item">
                                <a href="{{route('pages', ['legal/privacy'])}}" class="nav-link">
                                    Privacy Policy
                                </a>
                            </li>
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{route('pages', ['legal/cookies'])}}" class="nav-link">--}}
{{--                                    Cookie Policy--}}
{{--                                </a>--}}
{{--                            </li>--}}
                            <li class="nav-item">
                                <a href="{{route('pages', ['legal/terms'])}}" class="nav-link">
                                    Terms and Conditions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('pages', ['legal/accessibility'])}}" class="nav-link">
                                    Accessibility
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row copyright">
                    <div class="col">
                        <span>&copy; {{date('Y')}} Copyright, Age &amp; Peace | site by <a href="https://thedaylightstudio.com" target="_blank">Daylight</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
