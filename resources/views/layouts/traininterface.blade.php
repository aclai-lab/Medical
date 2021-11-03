<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
     <!-- Google Font: Source Sans Pro -->
  {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
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
{{-- <script src="{{asset('plugins/sparklines/sparkline.js')}}"></script> --}}
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
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

  
    <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-blue navbar-light ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      {{-- <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Projects</a>
      </li> --}}
    </ul>
    
     <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link float-right mx-2"  href="#" onclick="disclaimer()" >Disclaimer</a><!-- onclick="disclaimer()"-->
                    {{-- <button class="nav-link float-right mx-2" onclick="disclaimer()" >Disclaimer</button> --}}
                </li>
                
                <li>
                    <a class="nav-link" href="#" id="rsidebar" onclick="changeText()" data-widget="control-sidebar" >Show More Info</a>
                </li>
                 <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        {{-- @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif --}}
                    @else
                
                 <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                
                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" data-toggle="modal" id="mediumButton" data-target="#mediumModalAPIKEY" type="button"  onclick="getapi()" title="APIKEY" >
                                APIKEY
                                </a>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                              

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                         @endguest
                         </ul>
            </div>
        </div>
    </nav>
    
    
  
    
    <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{URL::action('HomeController@index')}}" class="brand-link">
      <svg class="brand-image img elevation-3" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-bootstrap" viewBox="0 0 16 16">
        <path d="M5.062 12h3.475c1.804 0 2.888-.908 2.888-2.396 0-1.102-.761-1.916-1.904-2.034v-.1c.832-.14 1.482-.93 1.482-1.816 0-1.3-.955-2.11-2.542-2.11H5.062V12zm1.313-4.875V4.658h1.78c.973 0 1.542.457 1.542 1.237 0 .802-.604 1.23-1.764 1.23H6.375zm0 3.762V8.162h1.822c1.236 0 1.887.463 1.887 1.348 0 .896-.627 1.377-1.811 1.377H6.375z"/>
        <path d="M0 4a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v8a4 4 0 0 1-4 4H4a4 4 0 0 1-4-4V4zm4-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V4a3 3 0 0 0-3-3H4z"/>
      </svg>
      {{-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
      <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      
  
      
       <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item "><!--d-flex justify-content-center -->
            <a class="nav-link " href="{{URL::action('HomeController@index')}}">
              <svg class="nav-icon fas fa-copy" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
              <p>Home</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{URL::action('QueryController@index')}}">
              <svg class="nav-icon fas fa-copy" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-medical" viewBox="0 0 16 16">
                <path d="M8.5 4.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L7 6l-.549.317a.5.5 0 1 0 .5.866l.549-.317V7.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L9 6l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V4.5zM5.5 9a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
              </svg>              <p>Projects</p> 
            </a>
          </li>
          
        
             
        </ul>

        {{-- <footer class="sidebar-footer">
          Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.
          All rights reserved.
          <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.1.0
          </div>
        </footer> --}}

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  
   <!-- medium modal -->
                 <div class="modal fade" id="mediumModalAPIKEY" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
                 aria-hidden="true">
                 <div class="modal-dialog" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                           Save yours APIKEY
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                             </button>
                         </div>
                         <div class="modal-body" id="mediumBodyAPIKEY">
                             <div>
                              {{-- <form action="{{URL::action('QueryController@saveApiKey')}}" method="POST"> --}}
                                {{csrf_field()}}
            
                                <input type="text" class="form-control" name="_token" id="_token" hidden>
                            
                                <div class="form-group">
                                  <label for="apikey">PubMed APIKEY</label>
                                  <input type="text" class="form-control" name="inputPubmed" id="inputPubmed">
                                  {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
                                </div>
                            
                                   
                                <div class="form-group">
                                    <label for="apikey">Scopus APIKEY</label>
                                    <input type="text" class="form-control" name="inputScopus" id="inputScopus">
                                    {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
                                </div>
                               
                                
                                
                            </div>
                            
                                <button type="submit" class="btn btn-primary" data-dismiss="modal" onclick="saveApi()">Save</button>
                                {{-- <a href="{{URL::action('HomeController@index')}}" class="btn btn-secondary ">Back</a> --}}
                 


                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             
   
        <main class="py-4">
            @yield('content')
        </main>

       


    <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
        <!-- Content of the sidebar goes here -->
        <div class="user-panel my-1">
          <p>Summary</p>
        </div>
        <div class="user-panel">
          <p >Current Traning</p>
          <p id="labelResult">Total labeling : 0</p>
        </div>

        <div class="user-panel my-1">
          <p>Current Status</p>
          <p id="accuracy">Accuracy : 0%</p>
          {{-- <p id="tpostive">True Positives : 0</p> 
          <p id="fpostive">False Positives : 0</p>
          <p id="tnegative">True Negatives : 0</p>
          <p id="fnegative">False Negatives : 0</p>   
>--}}
          <a data-toggle="modal" id="smallButtonmatrix" data-target="#mediumModal" type="button" class="btn btn-primary btn-lg btn-block my-2" onclick="showMatrix()" title="Matrix">Confusion Matrix</a>
        </div>

        <div class="user-panel my-1">
          <p>Traning Option</p>
          <div>
            <a data-toggle="modal" data-target="#mediumModalTrain" type="button" class="btn btn-primary btn-lg btn-block my-2" onclick="testTrain()" title="Train">
              Test
              </a>
            {{-- <button class="btn btn-primary btn-lg btn-block my-2" onclick="testTrain()">Test</button> --}}
          </div>

          <div>
            {{-- <button class="btn btn-primary btn-lg btn-block my-2" onclick="apply()">Apply</button> --}}
            <a data-toggle="modal" data-target="#mediumModalApply" type="button" class="btn btn-primary btn-lg btn-block my-2" onclick="apply()" title="Apply">
              Apply
              </a>
          </div>  

          <div>
            <button class="btn btn-primary btn-lg btn-block my-2" onclick="undo()">Undo</button>
          </div>

          {{-- <div>
            <button class="btn btn-primary btn-lg btn-block my-2" onclick="saveLabel()">Save labeling</button>
          </div> --}}

          <div>
            <button class="btn btn-primary btn-lg btn-block my-2" onclick="deleteLabel()">Forget labeling</button>
          </div>
        {{-- <a href="#" class="btn btn-primary float-md-left mb-2 mr-2">Test</a> --}}
        {{-- <a href="#" class="btn btn-primary float-md-left mb-2 ml-2 mr-2">Apply</a>  
        <a href="#" class="btn btn-primary float-md-left mb-2 ml-2 mr-2">Undo</a>  
        <a href="#" class="btn btn-primary float-md-left mb-2 ml-2 mr-2">Save labeling</a>   --}}
        </div>

    </div>
    </aside>
 
</div>

    <!-- jQuery -->

    {{-- <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script> --}}

    <div class="modal" tabindex="-1" role="dialog" id="mediumModalTrain">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Train Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="mediumBodyTrain">
              <div class="button-modal" style="display: none" id="train-info">
                <label id="info"></label>
                
              </div>

              <div class="button-modal" style="display: none" id="modal-error">
                <label id="error">Someone else is using the "test" function right now</label>
                
              </div>
            
            <div class="text-center" id="spinner-train" style="display: inline;">
             <div class="d-flex justify-content-center" >
              <div class="spinner-border text-primary" role="status">
                  <span class="sr-only">Loading...</span>
              </div>
            </div>
          </div>
    
          </div>
          {{-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div> --}}
        </div>
      </div>
    </div>


    <div class="modal" tabindex="-1" role="dialog" id="mediumModalApply">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Apply Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="mediumBodyApply">
              <div class="button-modal" style="display: none" id="apply-info">
                <label id="info"></label>
                
              </div>

              <div class="button-modal" style="display: none" id="apply-modal-error">
                <label id="error">Someone else is using the "apply" function right now</label>
                
              </div>
            
            <div class="text-center" id="spinner-apply" style="display: inline;">
             <div class="d-flex justify-content-center" >
              <div class="spinner-border text-primary" role="status">
                  <span class="sr-only">Loading...</span>
              </div>
            </div>
          </div>
    
          </div>
          {{-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div> --}}
        </div>
      </div>
    </div>

</body>
</html>
    
    <script>

$('document').ready(function(){

  // getapi();

  document.getElementById('rsidebar').click();

  localStorage.setItem('alerted','no');

});

/**
  @brief change the text of the button Show more info

  change the text of the button Show more info to Show Less info,
  the reverse case is Show Less info to Show More info

  @param none
  @return none
*/
function changeText()
{

    var text = document.getElementById("rsidebar").innerHTML ;

    if(text == "Show More Info")
        document.getElementById('rsidebar').innerHTML = 'Show Less Info';
    else
        document.getElementById('rsidebar').innerHTML = 'Show More Info';



}

// $('document').on('click', '#mediumButton', function(event) {
//             event.preventDefault();
//             let href = $(this).attr('data-attr')

//             getapi();

//             $.ajax({
//                 url: href,
//                 beforeSend: function() {
//                     $('#loader').show();
//                 },
//                 // return the result
//                 success: function(result) {
//                     $('#mediumModal').modal("show");
//                     $('#mediumBody').html(result).show();
//                 },
//                 complete: function() {
//                   // console.log("ho chiuso la finestra");
//                     $('#loader').hide();
                   
//                 },
//                 error: function(jqXHR, testStatus, error) {
//                     console.log(error);
//                     alert("Page " + href + " cannot open. Error:" + error);
//                     $('#loader').hide();
//                 },
//                 timeout: 8000
//             })
//         });


/**
   @brief retrive the api-key

   retrive the apikey of user and set the value in the text boxes of the modal

   @param none
   @return none
*/
function getapi(){


  $.ajax({
          url: '/getapikeyajax',
          type: 'get',
          dataType:'json',
          
          
          success: function(response){

            // console.log(response['data'].length);


              // console.log(response['data'][0]);
              // console.log(response['data'][1]);

              if(response['data'].length != 0)
              {
                for(var i = 0 ;i < response['data'].length;i++)
                {
                  switch(response['data'][i].id_database)
                  {

                      case 1:
                        document.getElementById("inputPubmed").value = response['data'][i].api_key;
                        break;

                      case 2:
                        document.getElementById("inputScopus").value = response['data'][i].api_key;
                        break;




                  }

                  // console.log(response['data'][i].user_name);

                }
              
              }
           
            
        
            },
            error:function(response,stato){
              
              console.log(stato);
            }
      }) //fine chiamata ajax

}

/**
   @brief save the api-key

   when the button is clicked will show a modal with two text boxes,
   if the user has already entered values ​​they will appear in the box 

   @param none
   @return none
*/
function saveApi(){

  var pubmed = document.getElementById("inputPubmed").value;
  var scopus = document.getElementById("inputScopus").value;
  var _token = document.getElementById("_token").value;
                    


$.ajax({
        url: '/saveApiKey',
        type: 'post',
        // dataType:'json',
        data:{'pubmed':pubmed,'scopus':scopus,'_token':_token},
        
        success: function(){

            // console.log("tutto bene");
          
      
          },
          error:function(stato){
            
            console.log(stato);
          }
    }) //fine chiamata ajax

}

/**
   @brief show the disclaimer

   when the button is clicked a pop-up opens showing the disclaimer text

   @param none
   @return none
*/
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


function trainBtn()
{
  





}




</script>
    

