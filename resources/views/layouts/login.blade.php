<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
     <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">

      <!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>


</head>
<body class="hold-transition sidebar-mini layout-fixed">
  
             
   
        <main class="py-4">
            @yield('content')
        </main>
 
</div>

</body>
</html>
    
    <script>


function disclaimer(){


  alert("Website Disclaimer\n\nLiability: For documents and software available from this server, the U.S. \nGovernment does not warrant or assume any legal liability or responsibility for the accuracy, completeness, or \n usefulness of any information, apparatus, product, or process disclosed.\n\n"
					+"Endorsement: NCBI does not endorse or recommend any commercial products, processes, or services. The \nviews and opinions of authors expressed on NCBI's Web sites do not necessarily state or reflect those of the \nU.S. Government, and they may not be used for advertising or product endorsement purposes.\n\n"
					+"External Links: Some NCBI Web pages may provide links to other Internet sites for the convenience of users. \nNCBI is not responsible for the availability or content of these external sites, nor does NCBI endorse, warrant, or \nguarantee the products, services, or information described or offered at these other Internet sites. Users cannot \nassume that the external sites will abide by the same Privacy Policy to which NCBI adheres. It is the \nresponsibility of the user to examine the copyright and licensing restrictions of linked pages and to secure \nall necessary permissions.\n\n"
					+"Pop-Up Advertisements: When visiting our Web site, your Web browser may produce pop-up advertisements. \nThese advertisements were most likely produced by other Web sites you visited or by third party software \ninstalled on your computer. The NLM does not endorse or recommend products or services for which you may \nview a pop-up advertisement on your computer screen while visiting our site.\n\n"
					+"Website Usage\n"
					+"This site is maintained by the U.S. Government and is protected by various provisions of Title 18 of the U.S. Code. \nViolations of Title 18 are subject to criminal prosecution in a federal court. For site security purposes, as well as \nto ensure that this service remains available to all users, we use software programs to monitor traffic and to \nidentify unauthorized attempts to upload or change information or otherwise cause damage. In the event of \nauthorized law enforcement investigations and pursuant to any required legal process, information from these \nsources may be used to help identify an individual.\n\n"
					+"Copyright Status of Webpages\n"
					+"Information that is created by or for the US government on this site is within the public domain. Public domain \ninformation on the National Library of Medicine (NLM) Web pages may be freely distributed and copied. However, \nit is requested that in any subsequent use of this work, NLM be given appropriate acknowledgment.\n\n"
					+"NOTE: This site contains resources which incorporate material contributed or licensed by individuals, \ncompanies, or organizations that may be protected by U.S. and foreign copyright laws. These include, but are not \nlimited to PubMed Central (PMC) (see PMC Copyright Notice), Bookshelf (see Bookshelf Copyright Notice), OMIM \n(see OMIM Copyright Status), and PubChem. All persons reproducing, redistributing, or making commercial use \nof this information are expected to adhere to the terms and conditions asserted by the copyright holder. \nTransmission or reproduction of protected items beyond that allowed by fair use (PDF) as defined in the \ncopyright laws requires the written permission of the copyright owners.\n\n"
					+"Accessibility Policy\n"
					+"As a Center within the National Library of Medicine (NLM), the NCBI is making every effort to ensure that the \ninformation available on our Web site is accessible to all. Please see the NLM's Accessibility Policy, for more \ninformation.\n\n"
					+"Privacy Policy\n"
					+"The NCBI provides this Web site as a public service. As a Center within the NLM, we do not collect any personally \nidentifiable information (PII) about visitors to our Web sites. We do collect some data about user visits to help us \nbetter understand how the public uses the site and how to make it more helpful. The NCBI does not collect \ninformation for commercial marketing or any purpose unrelated to NCBI's Mission. For more information, please \nsee the NLM Privacy Policy.\n\n"
					+"Medical Information and Advice Disclaimer\n"
					+"It is not the intention of NLM to provide specific medical advice but rather to provide users with information to \nbetter understand their health and their diagnosed disorders. Specific medical advice will not be provided, \nand NLM urges you to consult with a qualified physician for diagnosis and for answers to your personal \nquestions.\n\n"
					+"Guidelines for Scripting Calls to NCBI Servers\n"
					+"Do not overload NCBI's systems. Users intending to send numerous queries and/or retrieve large numbers of \nrecords should comply with the following:\n\n"
					+"- Run retrieval scripts on weekends or between 9 pm and 5 am Eastern Time weekdays for any series of more \n  than 100 requests.\n"
					+"- Send E-utilities requests to https://eutils.ncbi.nlm.nih.gov, not the standard NCBI Web address.\n"
					+"- Make no more than 3 requests every 1 second.\n"
					+"- Use the URL parameter email, and tool for distributed software, so that we can track your project and contact \n  you if there is a problem. For more information, please see the Usage Guidelines and Requirements section in \n  the Entrez Programming Utilities Help Manual.\n"
					+"- NCBI's Disclaimer and Copyright notice must be evident to users of your service. NLM does not claim the \n  copyright on the abstracts in PubMed; however, journal publishers or authors may. NLM provides no legal advice \n  concerning distribution of copyrighted materials, consult your legal counsel.\n"
  );
}







</script>
    

