@extends('layouts.basic')

@section('page-title', 'Accessibility Statement')
@section('og:title', 'Accessibility Statement')

@section('pageContent')
    <h1>Age and Peace Accessibility Statement</h1>

    <p>Age and Peace is committed to providing a website that is accessible to all users, including those with disabilities. We endeavor to follow those standards used by the Federal government for technology accessibility for people with disabilities (Section 508), and the Web Content Accessibility Guidelines (WCAG) 2.0 developed by the World Wide Web Consortium (W3C). Age and Peac’s web portal site is regularly tested using leading web accessibility technologies and we make accessibility updates frequently.</p>
    <p>For more information about the Federal standards, please visit the Section 508 web site <a href="http://www.section508.gov/content/learn/standards" target="_blank">http://www.section508.gov/content/learn/standards</a> or the Federal Access Board web site <a href="http://www.access-board.gov" target="_blank">http://www.access-board.gov</a>. Additionally, for more information about the WCAG 2.0 Standards, please visit their web site <a href="http://www.w3.org/TR/WCAG20/" target="_blank">http://www.w3.org/TR/WCAG20/</a>.</p>
    <p>In addition, some documents on Age and Peace's web site are produced in Portable Document Format (PDF). Efforts are currently underway to ensure that these files are produced using the latest version of Acrobat (file conversion software for PDFs). This is the most accessible technology currently available for these types of files. In order to improve viewing of these files, please download the latest version of Adobe Reader™, which is available for free at the Adobe website <a href="http://get.adobe.com/reader/" target="_blank">http://get.adobe.com/reader/</a>.</p>

    <h2>Contact Information</h2>
    <p>If any file format prevents you from accessing information on Age and Peace, please contact us for assistance by any of the means noted below. However you choose to contact us, in order to respond in a manner most helpful to you, please note the nature of your accessibility concern, the web page address of the requested material, and your email address so that we may best respond to your concern. We also welcome your questions about this accessibility statement, and comments on how to improve the site's accessibility.</p>

    <h2>Please contact us:</h2>
    <ul>
        <li>By Email: {!! mailto('info@ageandpeace.com') !!}</li>
        <li>By Phone: 503-395-2272</li>
    </ul>


@endsection
