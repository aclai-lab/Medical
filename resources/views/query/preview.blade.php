

<div class="container" class="col-md-12 col-sm-12 col-xs-12">


     <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Title</th>
            <th scope="col">Abstract</th>
          </tr>
        </thead>
        <tbody>

            @foreach ($data as $item)
                
            
            <tr>
              <th scope="row">{{$item["title"]}}</th>
              <td>{{$item["abstract"]}}</td>             
            @endforeach 
                  
          </tbody>
      </table> 
   
    
      {{-- <p>hello</p> --}}
  
  
</div><!-- chiusura div principale -->
  



