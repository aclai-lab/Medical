<div class="container" class="col-md-12 col-sm-12 col-xs-12">

  @if($data != null)

    @foreach ($data as $item)
      <table class="table table-striped">
        
        <thead>
          <tr>
            <th scope="col">{{$item["name"]}}</th>
            <th scope="col">N</th>
            <th scope="col">P</th>
          </tr>
        </thead>
        <tbody>
        
            

                      
                
                      <tr>
                        <th scope="row">N</th>
                        <td>{{$item["value"][0]}}</td>
                        <td>{{$item["value"][1]}}</td>  
                      </tr>    
                      <tr>
                      <th scope="row">P</th>
                        <td>{{$item["value"][2]}}</td>
                        <td>{{$item["value"][3]}}</td>  
                      </tr>   
                  
            
                  
          </tbody>

          
      </table> 
      @endforeach  
   
  
  @else
  <p>No matrix available</p>
  
  @endif
 
 
</div><!-- chiusura div principale -->