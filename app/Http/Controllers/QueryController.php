<?php

namespace App\Http\Controllers;

/**
 * @file QueryController
 * @date 11/10/21
 * @brief methods use in QueryController
 * 
 * 
 * 
 */

use App\Query;
use App\Define_by;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\LabelFlag;
use App\Label;
use App\Access;
use App\Author;
use App\Write;
use App\Pubblication;
use App\Journal;
use App\Book;
use App\Discover;
use App\Affiliation;
use App\Has;
use App\Find;
use App\Retrive;
use DB;
use Log;

class QueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index()
    {
        $id = Auth::user()->id;/**< Id of log user. */

        $q = Query::leftJoin('define_bies','queries.id','=','define_bies.id_query')->leftJoin('label_flags','label_flags.id_query','=','queries.id')->where('define_bies.id_user',$id)->select('queries.*',DB::raw("group_concat(label_flags.name SEPARATOR ';') as label"))->groupBy('queries.id')->get();/**< query of log user. */

        return view('query.index',compact('q'));/**< Return view */
    }

    /**
     * @brief create method use for creating a query
     * 
     * 
     * @param none
     * @return view query.create
     *
     * 
     */
    public function create()
    {
        return view('query.create');
    }

    /**
     * @brief Store a newly created resource in storage.
     * 
     * the store method takes care of saving queries that have never been created
     * the string, descriptions, results found of the query, the label_flag and the relationship define_by are saved
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();//get data

        $validateData = $request->validate([//if this data are not filled return error

            'name' => 'required',      
            'description_long' => 'required',
            'description_short' => 'required',
            'label'=>'required',
            'string'=>'required'

        ]);

        $apiKey=Access::where('id_user',Auth::user()->id)->where('id_database',1)->first()->api_key;//retrive apikey

        $stringa=str_replace(' ','+',$input['string']);//turns the raw string into a clean string

        $stringa=str_replace('"','',$stringa);//turns the raw string into a clean string

        $pre_exc = intval($this->fetchWithoutServer($stringa,$apiKey));//take the numer of result

        $id = Query::create([

                'name' => $input['name'],
                // 'project_type' => 'placeholder17',
                'description_long' => $input['description_long'],
                'description_short' => $input['description_short'],
                'pre_exc' => $pre_exc,
                'latest_exc_date' => null,
                'ret_start' => 0,
                'ret_max' => null,
                'exec_in_progress' => 0,
                'train_in_progress' => 0,
                'seed' => 0,
                'accuracy' => 0,
                'place' => null,
                'key_phrases' => 'asd',
                'string' => $input['string'],

        ])->id;//save query

        Define_by::create([
            'id_query' => $id,
            'id_user' => Auth::user()->id
        ]);//save define_by
        
        $token = explode(";",$input['label']); //split the label

        for($i=0;$i < count($token)-1; $i++)//save every label in label_flags
        {

            $idlabFlag = LabelFlag::create([
                'id_query' => $id,
                'name'=> $token[$i],
                
            ])->id;

        }

        return redirect('/query')->with('success','Nuovo progetto aggiunto con successo!');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Query $query)
    {
        //
    }

    /**
     * @brief Show the form for editing the specified resource.
     * 
     * creates the page for editing the query
     *
     * @param  \App\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function edit(Query $query)
    {
        $label = LabelFlag::where('id_query',$query->id)->select(DB::raw("group_concat(label_flags.name SEPARATOR ';') as name"))->get();//retrive query plus label_flag

        return view('query.edit',compact('query','label'));//show view
    }

    /**
     *@brief Update the specified resource in storage.
     *
     * the update method allows you to modify each value of the query if the labels are modified, the information of the labels is deleted 
     * and created with the new information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Query $query)
    {
        $validateData = $request->validate([//check data

            'name' => 'required',      
            'description_long' => 'required',
            'description_short' => 'required',
            'label'=>'required',
            'string'=>'required'

        ]);

        $input = $request->all();//recive input

        $query->name = $input['name'];
        $query->description_short = $input['description_short'];
        $query->description_long = $input['description_long'];

        $query->string = $input['string'];

        //label part
        $label =  LabelFlag::where('id_query',$query->id)->delete();//delete all label_flag row

        $token = explode(";",$input['label']); 

        for($i=0;$i < count($token)-1; $i++)
        {    
               
            LabelFlag::create([
                    'id_query' => $query->id,
                    'name'=> $token[$i],
                    
                ]);

        }

        $query->save();//save query

        return redirect('query');//return view to index
    }

    /**
     *@brief Remove the specified resource from storage.
     *
     * delete the query and every tuple associated with the query for laravel conventions
     * 
     * @param  \App\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function destroy(Query $query)
    {
        $query->delete();//delete query 

        return redirect('query');//reutrn to index
    }

    /**
     * @brief fetch data from pubmed query never executed
     * 
     * The fromstart method prepares the values ​​for the pubmed xml with retstart equal to 0 via the fetch method
     * 
     * 
     * @see fetch
     * @param Request $request
     * @return none
     */

    public function fromstart(Request $request)
    {
        set_time_limit(3600);//time limit of 1h to prevent interruptions

        $input = $request->all();//recive input

        $apiKey=Access::where('id_user',Auth::user()->id)->where('id_database',1)->first()->api_key;;//retrive apikey

        $string = Query::where('id',$input['id'])->first()->string;//retive query string

        $stringa=str_replace(' ','+',$string);

        $stringa=str_replace('"','',$stringa);

        $count = Query::where('id',$input['id'])->first()->pre_exc;

        $percent = intval($input['percent']);

        $this->fetch($stringa,$apiKey,$count,$percent,$input['id']);
    }

    /**
     * @brief fetch data from pubmed query executed
     * 
     * The fromstart method prepares the values ​​for the pubmed xml with retstart different from 0 via the fetchStop method
     * 
     * 
     * @see fetchStop
     * @param Request $request
     * @return none
     */
    public function fromstop(Request $request)
    {
        set_time_limit(3600);//time limit of 1h to prevent interruptions

        $input = $request->all();//recive input

        $apiKey=Access::where('id_user',Auth::user()->id)->where('id_database',1)->first()->api_key;;//retrive apikey

        $string = Query::where('id',$input['id'])->first()->string;//retrive string

        $stringa=str_replace(' ','+',$string);//clean string

        $stringa=str_replace('"','',$stringa);//clean string

        $count = Query::where('id',$input['id'])->first()->pre_exc;//retrive number of results

        $retStart = Query::where('id',$input['id'])->first()->ret_start;//retrive ret start

        $percent = intval($input['percent']);//retrive percentage required

        $this->fetchStop($stringa,$apiKey,$count,$percent,$input['id'],$retStart);
    }

    /**
     * @brief check the percentage of date withdrawn
     * 
     * it collects the information of ret_start, pre_exc and calculates the percentage by sending the information via string
     * 
     * @param Request $request
     * @return $string with $start,$count and $percent
     */
    public function checkretstart(Request $request)
    {
        $input = $request->all();//recive input

        $start = Query::where('id',$input['id'])->first()->ret_start;//retrive retstart

        $count = Query::where('id',$input['id'])->first()->pre_exc;//retrive numer of results

        $percent = ($start/$count)*100;//find how much percentage made

        $string=$start."|".$percent."|".$count;//put everuthing a in string

        echo json_encode($string);//send string to javascript (ajax)
    }

    /**
     * @brief prepare data for the method fetchResume
     * 
     * @param Request $request
     * @return none
     */
    public function fromResume(Request $request)
    {
        set_time_limit(3600);//set time limit to 1h

        $input = $request->all();//recive all input

        $apiKey=Access::where('id_user',Auth::user()->id)->where('id_database',1)->first()->api_key;//retrive apikey

        $string = Query::where('id',$input['id'])->first()->string;//retrive string

        $stringa=str_replace(' ','+',$string);//clean string

        $stringa=str_replace('"','',$stringa);//clean string

        $start = Query::where('id',$input['id'])->first()->ret_start;//recive ret start

        $count = Query::where('id',$input['id'])->first()->pre_exc;//recive number of results

        $percent = intval($input['percent']);//percentage choose

        $this->fetchResume($stringa,$apiKey,$count,$percent,$input['id'],$start);
    }

    /**
     * @brief fetch xml from pubmed with restart different from 0
     * 
     * take the webserver from the generated link and cycles reaching the requested percentage,
     * a calculation is made of the number of results corresponding to the requested percentage
     * 
     * 
     * @param Request $request
     * @return none
     */
    private function fetchResume($stringa,$apiKey,$count,$percent,$id,$start){

        $link = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=".$stringa."&usehistory=y&api_key=".$apiKey;//con questo devo recuperare gli id e il webEnv

        $result = file_get_contents($link);//get content of xml 

        $xml = simplexml_load_string($result);//red xml 

        $webEnv=$xml->WebEnv;//get webenv

        $queryKey=$xml->QueryKey;//get query key

        $retStart = $start; $retMax = 1000;
        $nCicli = $count / $retMax;//number of cycle
        $percent /= 100;//percentage decimal format 
        $nCicli=intval($nCicli *= $percent );//number of cycle necessary 

        for($i = 0 ; $i <= $nCicli; $i++)
        {
            $linkFetch = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&WebEnv=".$webEnv."&query_key=".$queryKey."&retstart=".$retStart."&retmax=".$retMax."&retmode=xml&api_key=".$apiKey;

                //    echo $linkFetch;
            $resultFetch = file_get_contents($linkFetch);//get content

            $xmlFetch = simplexml_load_string($resultFetch);//xml fetch ha al suo interno tutti i dati rigurdante la query string inserita in pubmed

            foreach($xmlFetch->children() as $item){

                    //item node name
                    if(strcmp($item->getName(),"PubmedArticle") === 0) //questa è la chiave per la distinzione tra book e article ed errore
                        $this->readArticleDatabase($item,$id);
                    else if(strcmp($item->getName(),"PubmedBookArticle") === 0)
                        $this->readBookDatabase($item,$id);
                    else
                        echo "error";
            }

            $retStart+=$retMax;//update ret start
        }//fine for

        Query::where('id',$id)->update(['ret_start' => $retStart]);//update query
    }//fine fetch

    /**
     * @brief retrive data from xmlpumed
     * 
     * the executefstart is not use anymore use fetchResume o fetchStop
     * 
     * @see fetchResume
     * @see fetchStop
     * @param $id
     * @return query.execute
     */
    public function executefstart($id)
    {
        $apiKey=Access::where('id_user',Auth::user()->id)->where('id_database',1)->first()->api_key;

        $stringa=str_replace(' ','+',$query->string);

        $stringa=str_replace('"','',$stringa);

        $count = $query->pre_exc;

        $data = $this->fetch($stringa,$apiKey,$id);

        return view('query.execute',compact('query','data'));
    }

    /**
     * @brief create the train page
     * 
     * @param $id
     * @return query.train
     */
    public function train($id)
    {
        $query = Query::where('id',$id)->select('*')->get();//get all info of query

        $pubb = Discover::where('id_query',$id)->get();//che id pubblication where = query.id

        $auth = Author::leftJoin('writes','writes.id_author','=','authors.id')->leftJoin('pubblications','writes.id_pubblication','=','pubblications.id')->leftJoin('discovers','pubblications.id','=','discovers.id_pubblication')->where('discovers.id_query',$id)->select('pubblications.id as pubId','authors.*')->get();

        $label = LabelFlag::where('id_query',$id)->select()->get();

        return view('query.train',compact('pubb','label','query','id','auth'));
    }


    /**
     * @brief take the number of result found by the query
     * 
     * @param $stringa,$apiKey
     * @return data (the number)
     */
    private function fetchWithoutServer($stringa,$apiKey){

        $link = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=".$stringa."&usehistory=y&api_key=".$apiKey;//con questo devo recuperare gli id e il webEnv

        $result = file_get_contents($link);//get content

        $xml = simplexml_load_string($result);//read xml

        $data = $xml->Count;//take number of results   

       return $data;

    }

    /**
     * @brief method for the content of modal for preview
     * 
     * 
     * call the first 20 result of the query and print them in a large modal, to retrive the information through fetchpreviw
     * 
     * @see fetchPreview
     * @param $string
     * @return view query.preview
     * 
     */ 
    public function preview($string){

        $data=$this->fetchPreview($string);//call method

        return view('query.preview',compact('data'));//send data for the modal
    }

    /**
     * @brief method for the content of modal for confusion matrix
     * 
     * 
     * print the confusion matrix in a modal function 
     * 
     * @param $id
     * @return view query.showmatrix
     * 
     */ 
    public function showconfusionmatrix($id){

        $string = Query::where('id',$id)->first()->matrix;//retrive confusion matrix string

        $class = DB::select('select label_flags.name from label_flags where label_flags.id_query = ?',[$id]);//get name of class (label_flag)

        $data=[];
        $c = 0;

        $tok=explode("/",$string);//this means that the matrix of a class is over

        foreach($tok as $single)
        {
                $value = explode(" ",$single);//remove empty spaces

                $push = [
                    'name' => $class[$c]->name,
                    'value' => $value
                ];

                array_push($data,$push);
                $c++;
        }

        return view('query.showmatrix',compact('data'));//send array with object 
    }

    /**
     * @brief method for the content of modal for typeexecute
     * 
     * @param $id
     * @return view query.typeexecute
     * 
     */ 
    public function typeexecute($id)
    {
        $q=Query::where('id',$id)->get();

        return view('query.typeexecute',compact('q'));
    }

    /**
     * @brief method for the page analysis.builder
     * 
     * create the page to choose the type of analyses
     * 
     * @param $id
     * @return view analysis.builder
     * 
     */ 
    public function build($id)
    {
        return view('analysis.builder',compact('id'));
    }


    /**
     * @brief create the result of the analysis.builder page
     * 
     * based on the result of the analysis.builder page, a different search query is performed, the types are 4 "time", "geo", "venue", "author"
     * 
     * 
     * @param Request $request
     * @return is different for each type of analysis
     * 
     */ 
    public function result(Request $request)
    {
        $validateData = $request->validate([

            'type-of' => 'required'

        ]);

        $input = $request->all();

        $id = $input['id_query'];
        $type = $input['type-of'];

        $lineChart=[];

        switch ($type)
        {
            case "geo":

                $start = $input['start-time-geo'];

                if($start === null )
                {
                    $start = 0;
                }

                $end = $input['end-time-geo'];

                if($end === null )
                {
                    $end = 3000;
                }

                $result = DB::select('select label_flags.name,count(*) as counter,affiliations.country from labels,label_flags,pubblications,writes,authors,has,affiliations 
                where labels.id_query = ? and label_flags.id = labels.id_labels and labels.id_pubblication = pubblications.id and pubblications.id = writes.id_pubblication  
                and writes.id_author = authors.id and authors.id = has.id_author and has.id_affiliation = affiliations.id and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null)
                group by label_flags.name,affiliations.country',[$id,$start,$end]);
               

                $result_name = DB::select('select label_flags.name from labels,label_flags,pubblications,writes,authors,has,affiliations 
                where labels.id_query = ? and label_flags.id = labels.id_labels and labels.id_pubblication = pubblications.id and pubblications.id = writes.id_pubblication  
                and writes.id_author = authors.id and authors.id = has.id_author and has.id_affiliation = affiliations.id and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null)
                group by label_flags.name,affiliations.country',[$id,$start,$end]);
               
                $result_count = DB::select('select count(*) as counter from labels,label_flags,pubblications,writes,authors,has,affiliations 
                where labels.id_query = ? and label_flags.id = labels.id_labels and labels.id_pubblication = pubblications.id and pubblications.id = writes.id_pubblication  
                and writes.id_author = authors.id and authors.id = has.id_author and has.id_affiliation = affiliations.id and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null)
                group by label_flags.name,affiliations.country',[$id,$start,$end]);
               

                $result_country = DB::select('select affiliations.country from labels,label_flags,pubblications,writes,authors,has,affiliations 
                where labels.id_query = ? and label_flags.id = labels.id_labels and labels.id_pubblication = pubblications.id and pubblications.id = writes.id_pubblication  
                and writes.id_author = authors.id and authors.id = has.id_author and has.id_affiliation = affiliations.id and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null)
                group by label_flags.name,affiliations.country',[$id,$start,$end]);
               
                $groupid = LabelFlag::where('id_query',$id)->get();

                $counrty = DB::select('select affiliations.country from labels,label_flags,pubblications,writes,authors,has,affiliations 
                where labels.id_query = ? and label_flags.id = labels.id_labels and labels.id_pubblication = pubblications.id and pubblications.id = writes.id_pubblication  
                and writes.id_author = authors.id and authors.id = has.id_author and has.id_affiliation = affiliations.id and (pubblications.pubblication_year between "0" and "3000" or pubblications.pubblication_year is null)
                group by affiliations.country',[$id,$start,$end]);

               
                // $lineChart;
                foreach ($groupid as $idL)
                {

                    foreach ($counrty as $c)
                    {

                        array_push($lineChart,DB::select('select label_flags.name,count(*) as counter,affiliations.country,pubblications.pubblication_year from labels,label_flags,pubblications,writes,authors,has,affiliations 
                        where labels.id_query = ? and affiliations.country = ? and labels.id_labels = ?  and label_flags.id = labels.id_labels and labels.id_pubblication = pubblications.id and pubblications.id = writes.id_pubblication  
                        and writes.id_author = authors.id and authors.id = has.id_author and has.id_affiliation = affiliations.id and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null)
                        group by label_flags.name,affiliations.country,pubblications.pubblication_year',[$id,$c->country,$idL->id,$start,$end]));

                    }

                }
               
                return view('analysis.resultGeo',compact('id','result','result_name','result_count','result_country','lineChart'));
                break;

            case "time":

                $start = $input['start-time'];

                if($start === null )
                {
                    $start = 0;
                }

                $end = $input['end-time'];

                if($end === null )
                {
                    $end = 3000;
                }

                $result = DB::select('select label_flags.name,count(labels.id_labels) as counter,pubblications.pubblication_year from labels,label_flags,pubblications where label_flags.id = labels.id_labels 
                and labels.id_pubblication = pubblications.id and labels.id_query = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,pubblications.pubblication_year',[$id,$start,$end]);

                $result_name = DB::select('select label_flags.name from labels,label_flags,pubblications where label_flags.id = labels.id_labels 
                and labels.id_pubblication = pubblications.id and labels.id_query = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,pubblications.pubblication_year',[$id,$start,$end]);

                $result_count = DB::select('select count(labels.id_labels) as counter from labels,label_flags,pubblications where label_flags.id = labels.id_labels 
                and labels.id_pubblication = pubblications.id and labels.id_query = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,pubblications.pubblication_year',[$id,$start,$end]);

                $result_year = DB::select('select pubblications.pubblication_year from labels,label_flags,pubblications where label_flags.id = labels.id_labels 
                and labels.id_pubblication = pubblications.id and labels.id_query = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,pubblications.pubblication_year',[$id,$start,$end]);

                $groupid = LabelFlag::where('id_query',$id)->get();

                foreach ($groupid as $idL)
                {
                    array_push($lineChart,DB::select('select label_flags.name,count(labels.id_labels) as counter,pubblications.pubblication_year from labels,label_flags,pubblications where label_flags.id = labels.id_labels 
                    and labels.id_pubblication = pubblications.id and labels.id_query = ? and labels.id_labels = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,pubblications.pubblication_year',[$id,$idL->id,$start,$end]));
                }


                return view('analysis.resultTime',compact('id','result','result_name','result_count','result_year','lineChart'));

                break;

            case "venue":

                $start = $input['start-time-venue'];

                if($start === null )
                {
                    $start = 0;
                }

                $end = $input['end-time-venue'];

                if($end === null )
                {
                    $end = 3000;
                }

                $result_name_j = DB::select('select label_flags.name from labels,label_flags,pubblications,journals where label_flags.id = labels.id_labels and labels.id_query = ? 
                and labels.id_pubblication = pubblications.id and 
                (pubblications.id = journals.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name',[$id,$start,$end]);

                $result_count_j = DB::select('select count(labels.id_labels) as counter from labels,label_flags,pubblications,journals where label_flags.id = labels.id_labels and labels.id_query = ? 
                and labels.id_pubblication = pubblications.id and 
                (pubblications.id = journals.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name',[$id,$start,$end]);

                $result_name_b = DB::select('select label_flags.name from labels,label_flags,pubblications,books where label_flags.id = labels.id_labels and labels.id_query = ?
                 and labels.id_pubblication = pubblications.id and 
                (pubblications.id = books.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name',[$id,$start,$end]);

                $result_count_b = DB::select('select count(labels.id_labels) as counter from labels,label_flags,pubblications,books where label_flags.id = labels.id_labels and labels.id_query = ?
                and labels.id_pubblication = pubblications.id and 
               (pubblications.id = books.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name',[$id,$start,$end]);

                $result_name_bc = DB::select('select label_flags.name from labels,label_flags,pubblications,book_chapters where label_flags.id = labels.id_labels 
                and labels.id_query = ? and labels.id_pubblication = pubblications.id and 
                (pubblications.id = book_chapters.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name',[$id,$start,$end]);

                $result_count_bc = DB::select('select count(labels.id_labels) as counter from labels,label_flags,pubblications,book_chapters where label_flags.id = labels.id_labels 
                and labels.id_query = ? and labels.id_pubblication = pubblications.id and 
                (pubblications.id = book_chapters.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name',[$id,$start,$end]);

                $result_name_c = DB::select('select label_flags.name from labels,label_flags,pubblications,conferences where label_flags.id = labels.id_labels 
                and labels.id_query = ? and labels.id_pubblication = pubblications.id and 
                (pubblications.id = conferences.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name',[$id,$start,$end]);

                $result_count_c = DB::select('select count(labels.id_labels) as counter from labels,label_flags,pubblications,conferences where label_flags.id = labels.id_labels 
                and labels.id_query = ? and labels.id_pubblication = pubblications.id and 
                (pubblications.id = conferences.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name',[$id,$start,$end]);

                $groupid = LabelFlag::where('id_query',$id)->get();

                foreach ($groupid as $idL)
                {
                    array_push($lineChart,DB::select('select label_flags.name,count(labels.id_labels) as counter,pubblications.pubblication_year  from labels,label_flags,pubblications,journals where label_flags.id = labels.id_labels 
                    and labels.id_labels = ? and labels.id_query = ? 
                    and labels.id_pubblication = pubblications.id and (pubblications.id = journals.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,pubblications.pubblication_year ',[$idL->id,$id,$start,$end]));


                    array_push($lineChart,DB::select('select label_flags.name,count(labels.id_labels) as counter,pubblications.pubblication_year  from labels,label_flags,pubblications,books where label_flags.id = labels.id_labels 
                    and labels.id_labels = ? and labels.id_query = ? and labels.id_pubblication = pubblications.id and 
                    (pubblications.id = books.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,pubblications.pubblication_year ',[$idL->id,$id,$start,$end]));


                    array_push($lineChart,DB::select('select label_flags.name,count(labels.id_labels) as counter,pubblications.pubblication_year  from labels,label_flags,pubblications,conferences 
                    where label_flags.id = labels.id_labels and labels.id_labels = ? and labels.id_query = ? and labels.id_pubblication = pubblications.id and 
                    (pubblications.id = conferences.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,pubblications.pubblication_year ',[$idL->id,$id,$start,$end]));


                    array_push($lineChart,DB::select('select label_flags.name,count(labels.id_labels) as counter,pubblications.pubblication_year  from labels,label_flags,pubblications,book_chapters 
                    where label_flags.id = labels.id_labels and labels.id_labels = ? and labels.id_query = ? and labels.id_pubblication = pubblications.id and 
                    (pubblications.id = book_chapters.id_pubblication) and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,pubblications.pubblication_year ',[$idL->id,$id,$start,$end]));

                }

                return view('analysis.resultVenue',compact('id','result_name_j','result_count_j','result_name_b','result_count_b','result_name_c','result_count_c','result_name_bc','result_count_bc','lineChart'));
               
                break;
            case "author":

                $lineChartLastAuthor=[];

                $start = $input['start-time-author'];

                if($start === null )
                {
                    $start = 0;
                }

                $end = $input['end-time-author'];

                if($end === null )
                {
                    $end = 3000;
                }


                $result_fa_fn = DB::select('select authors.first_name from labels,label_flags,pubblications,writes,authors where label_flags.id = labels.id_labels 
                and writes.id_author = authors.id and writes.author_number = 1 and pubblications.id = writes.id_pubblication and labels.id_pubblication = pubblications.id 
                and labels.id_query = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,authors.id',[$id,$start,$end]);
                
                $result_fa_ln = DB::select('select authors.last_name from labels,label_flags,pubblications,writes,authors where label_flags.id = labels.id_labels 
                and writes.id_author = authors.id and writes.author_number = 1 and pubblications.id = writes.id_pubblication and labels.id_pubblication = pubblications.id 
                and labels.id_query = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,authors.id',[$id,$start,$end]);
                
                $result_fa_name = DB::select('select label_flags.name from labels,label_flags,pubblications,writes,authors where label_flags.id = labels.id_labels 
                and writes.id_author = authors.id and writes.author_number = 1 and pubblications.id = writes.id_pubblication and labels.id_pubblication = pubblications.id 
                and labels.id_query = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,authors.id',[$id,$start,$end]);
                
                $result_fa_count = DB::select('select count(labels.id_labels) as counter from labels,label_flags,pubblications,writes,authors where label_flags.id = labels.id_labels 
                and writes.id_author = authors.id and writes.author_number = 1 and pubblications.id = writes.id_pubblication and labels.id_pubblication = pubblications.id 
                and labels.id_query = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,authors.id',[$id,$start,$end]);
                

                $result_la_fn = DB::select('select authors.first_name 
                from labels,label_flags,pubblications,authors,(
                    select  writes.id_pubblication,authors.*
                    from authors,writes,(
                        select writes.id_pubblication as i, max(writes.author_number) as m 
                        from writes
                        group by writes.id_pubblication) as R 
                    where authors.id = writes.id_author and writes.author_number = R.m and writes.id_pubblication = R.i) as S
                where labels.id_pubblication = S.id_pubblication and S.id_pubblication = pubblications.id 
                and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) and authors.id = S.id 
                and labels.id_labels = label_flags.id and labels.id_query = ?
                group by labels.id_labels,authors.id',[$start,$end,$id]);
                
                $result_la_ln = DB::select('select authors.last_name 
                from labels,label_flags,pubblications,authors,(
                    select  writes.id_pubblication,authors.*
                    from authors,writes,(
                        select writes.id_pubblication as i, max(writes.author_number) as m 
                        from writes
                        group by writes.id_pubblication) as R 
                    where authors.id = writes.id_author and writes.author_number = R.m and writes.id_pubblication = R.i) as S
                where labels.id_pubblication = S.id_pubblication and S.id_pubblication = pubblications.id 
                and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) and authors.id = S.id 
                and labels.id_labels = label_flags.id and labels.id_query = ?
                group by labels.id_labels,authors.id',[$start,$end,$id]);


                $result_la_name = DB::select('select label_flags.name 
                from labels,label_flags,pubblications,authors,(
                    select  writes.id_pubblication,authors.*
                    from authors,writes,(
                        select writes.id_pubblication as i, max(writes.author_number) as m 
                        from writes
                        group by writes.id_pubblication) as R 
                    where authors.id = writes.id_author and writes.author_number = R.m and writes.id_pubblication = R.i) as S
                where labels.id_pubblication = S.id_pubblication and S.id_pubblication = pubblications.id 
                and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) and authors.id = S.id 
                and labels.id_labels = label_flags.id and labels.id_query = ?
                group by labels.id_labels,authors.id',[$start,$end,$id]);

                $result_la_count = DB::select('select count(*) as counter 
                from labels,label_flags,pubblications,authors,(
                    select  writes.id_pubblication,authors.*
                    from authors,writes,(
                        select writes.id_pubblication as i, max(writes.author_number) as m 
                        from writes
                        group by writes.id_pubblication) as R 
                    where authors.id = writes.id_author and writes.author_number = R.m and writes.id_pubblication = R.i) as S
                where labels.id_pubblication = S.id_pubblication and S.id_pubblication = pubblications.id 
                and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) and authors.id = S.id 
                and labels.id_labels = label_flags.id and labels.id_query = ?
                group by labels.id_labels,authors.id',[$start,$end,$id]);

                $groupid = LabelFlag::where('id_query',$id)->get();

                $groupIdAuthorFirst = DB::select('select authors.id from labels,label_flags,pubblications,writes,authors where label_flags.id = labels.id_labels 
                and writes.id_author = authors.id and writes.author_number = 1 and pubblications.id = writes.id_pubblication and labels.id_pubblication = pubblications.id 
                and labels.id_query = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,authors.id',[$id,$start,$end]);

                $groupIdAuthorLast = DB::select('select authors.id 
                from labels,label_flags,pubblications,authors,(
                    select  writes.id_pubblication,authors.*
                    from authors,writes,(
                        select writes.id_pubblication as i, max(writes.author_number) as m 
                        from writes
                        group by writes.id_pubblication) as R 
                    where authors.id = writes.id_author and writes.author_number = R.m and writes.id_pubblication = R.i) as S
                where labels.id_pubblication = S.id_pubblication and S.id_pubblication = pubblications.id 
                and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) and authors.id = S.id 
                and labels.id_labels = label_flags.id and labels.id_query = ?
                group by labels.id_labels,authors.id',[$start,$end,$id]);


                foreach ($groupid as $idL)
                {

                    foreach ($groupIdAuthorFirst as $idA)
                    {
                        array_push($lineChart,DB::select('select authors.first_name,authors.last_name,label_flags.name,count(labels.id_labels) as counter,pubblications.pubblication_year from labels,label_flags,pubblications,writes,authors where label_flags.id = labels.id_labels 
                        and writes.id_author = authors.id and writes.author_number = 1 and pubblications.id = writes.id_pubblication and labels.id_pubblication = pubblications.id 
                        and labels.id_query = ? and writes.id_author = ? and labels.id_labels = ? and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) group by label_flags.name,authors.id,pubblications.pubblication_year',[$id,$idA->id,$idL->id,$start,$end]));//first autore

                    }

                    foreach ($groupIdAuthorLast as $idA)
                    {
                        array_push($lineChartLastAuthor,DB::select('select label_flags.name, authors.first_name ,authors.last_name ,count(*) as counter,pubblications.pubblication_year
                        from labels,label_flags,pubblications,authors,(
                            select  writes.id_pubblication,authors.*
                            from authors,writes,(
                                select writes.id_pubblication as i, max(writes.author_number) as m 
                                from writes
                                group by writes.id_pubblication) as R 
                            where authors.id = writes.id_author and writes.author_number = R.m and writes.id_pubblication = R.i) as S
                        where labels.id_pubblication = S.id_pubblication and S.id_pubblication = pubblications.id 
                        and (pubblications.pubblication_year between ? and ? or pubblications.pubblication_year is null) and authors.id = S.id 
                        and labels.id_labels = label_flags.id and labels.id_query = ?  and authors.id = ? and labels.id_labels = ?
                        group by labels.id_labels,authors.id,pubblications.pubblication_year',[$start,$end,$id,$idA->id,$idL->id] ));

                    }
                }
                
                return view('analysis.resultAuthor',compact('id','result_fa_fn','result_fa_ln','result_fa_name','result_fa_count','result_la_fn','result_la_ln','result_la_name','result_la_count','lineChart','lineChartLastAuthor'));
                break;
        }
       
        return view('analysis.result',compact('id','result'));
    }

    /**
     * @brief retrive data for preview
     * 
     * create the link for the preview of pumed and collect the id and passes it to readFetch
     * 
     * @see readFetch
     * @param $query
     * @return $data
     * 
     */
    public function fetchPreview($query){

        $string = $query;//string of the query

        $apiKey=Access::where('id_user',Auth::user()->id)->where('id_database',1)->first()->api_key;//get apikey

        $stringa=str_replace(' ','+',$string);//clean string

        $stringa=str_replace('"','',$stringa);//clean string

        $link = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=".$stringa."&usehistory=y&api_key=".$apiKey;//con questo devo recuperare gli id e il webEnv

        $result = file_get_contents($link);//get content

        $xml = simplexml_load_string($result);//get xml

        $id="";
        
        foreach($xml->IdList->Id as $item){

            $id .= $item . ",";//create a big list of id in a string with "," separator

        }
        
        $data = $this->readFetch($id,$apiKey);//retrive data from readFetch

       return $data;
    }

    /**
     * @brief read xml for preview
     * 
     * based on the pubmed xml node, the type of reading to be done is chosen as they have different values
     * 
     * 
     * @param $idList,$apiKey
     * @return $data
     * 
     */
    private function readFetch($idList,$apiKey){

        $link = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=".$idList."&retmode=xml&api_key=".$apiKey;//con questo devo recuperare gli id e il webEnv

        $result = file_get_contents($link);//get content

        $xml = simplexml_load_string($result);//get xml

        $data=[];//array with the final results

        foreach($xml->children() as $item){

                    //item node name
                    if(strcmp($item->getName(),"PubmedArticle") === 0) //questa è la chiave per la distinzione tra book e article ed errore
                        $new = $this->readArticle($item);
                    else if(strcmp($item->getName(),"PubmedBookArticle") === 0)
                        $new = $this->readBook($item);
                    else
                        echo "error";

                    array_push($data,$new);//push data in array "data"
        }

        return $data;
    }//fine readfetch


    /**
     * @brief retrive the xml from pubmed with $retstart = 0 use for preview of the query
     * 
     * 
     * this method is not implemented anymore
     * 
     * @see fetchResume 
     * @see fetchStop
     * @param $stringa,$apiKey,$count,$percent,$id
     * @return none
     */
    private function fetch($stringa,$apiKey,$count,$percent,$id){

        $link = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=".$stringa."&usehistory=y&api_key=".$apiKey;//con questo devo recuperare gli id e il webEnv

        $result = file_get_contents($link);

        $xml = simplexml_load_string($result);

        $webEnv=$xml->WebEnv;

        $queryKey=$xml->QueryKey;

        $retStart = 0; $retMax = 1000;

        $nCicli = $count / $retMax;
        $percent /= 100;
        $nCicli=intval($nCicli *= $percent );

        for($i = 0 ; $i <= $nCicli; $i++)
        {
            try 
            {
                $linkFetch = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&WebEnv=".$webEnv."&query_key=".$queryKey."&retstart=".$retStart."&retmax=".$retMax."&retmode=xml&api_key=".$apiKey;

                $resultFetch = file_get_contents($linkFetch);

                $xmlFetch = simplexml_load_string($resultFetch);//xml fetch ha al suo interno tutti i dati rigurdante la query string inserita in pubmed

                foreach($xmlFetch->children() as $item){

                        //item node name
                        if(strcmp($item->getName(),"PubmedArticle") === 0) //questa è la chiave per la distinzione tra book e article ed errore
                            $this->readArticleDatabase($item,$id);
                        else if(strcmp($item->getName(),"PubmedBookArticle") === 0)
                            $this->readBookDatabase($item,$id);
                        else
                            echo "error";

                }

            } 
            catch (Exception $e) 
            {
                //throw $th;
            }

            $retStart+=$retMax;
        }//fine for

        Query::where('id',$id)->update(['ret_start' => $retStart]);
    }//fine fetch


    /**
     * @brief retrive the xml from pubmed with $retstart different from 0
     * 
     * take the webserver from the generated link and cycles reaching the requested percentage,
     * a calculation is made of the number of results corresponding to the requested percentage
     * 
     * based on the pubmed xml node, the type of reading to be done is chosen as they have different values foreach nodes
     * 
     * @param $stringa,$apiKey,$count,$percent,$id
     * @return none
     */
    private function fetchStop($stringa,$apiKey,$count,$percent,$id,$retStart){

        $link = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=".$stringa."&usehistory=y&api_key=".$apiKey;//con questo devo recuperare gli id e il webEnv

        $result = file_get_contents($link);//get contents 

        $xml = simplexml_load_string($result);//get xml

        $webEnv=$xml->WebEnv;//retrive webenv

        $queryKey=$xml->QueryKey;//retrive query key

        $retMax = 1000;
        // $nCicli = $count / $retMax;//find the number of cycle need it
        // $percent /= 100;//convert the percentage to decimal
        // $nCicli=intval($nCicli *= 0.1 );//find the number of cycle for 10%

        $previuosPercent = (($retStart/$count)*100);//calculate how much percentage has already been calculated
        $nCicli = intval(intval($percent - $previuosPercent)/10);//calcute the number of cycle 

        for($i = 0 ; $i < $nCicli; $i++)
        {

            try 
            {
                $linkFetch = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&WebEnv=".$webEnv."&query_key=".$queryKey."&retstart=".$retStart."&retmax=".$retMax."&retmode=xml&api_key=".$apiKey;

                $resultFetch = file_get_contents($linkFetch);//get content

                $xmlFetch = simplexml_load_string($resultFetch);//xml fetch ha al suo interno tutti i dati rigurdante la query string inserita in pubmed

                foreach($xmlFetch->children() as $item){

                        //item node name
                        if(strcmp($item->getName(),"PubmedArticle") === 0) //questa è la chiave per la distinzione tra book e article ed errore
                            $this->readArticleDatabase($item,$id);
                        else if(strcmp($item->getName(),"PubmedBookArticle") === 0)
                            $this->readBookDatabase($item,$id);
                        else
                            echo "error";
                }
            }
            catch (Throwable $th)
            {
                //throw $th;
            }
           

            $retStart+=$retMax;//update ret start
        }//fine for

        Query::where('id',$id)->update(['ret_start' => $retStart]);//update query ret start
    }//fine fetch

    /**
     * @brief read the article from xmlPubmed for preview
     * 
     * @param $item
     * @return $new
     * 
     */ 
    private function readArticle($item){
        $auth=[];

        //Title
        $title = $item->MedlineCitation->Article->ArticleTitle;
        //  echo $item->MedlineCitation->Article->ArticleTitle ."<br />"; 

        //abstract
        $abstract = $item->MedlineCitation->Article->Abstract->AbstractText;
        //  echo $item->MedlineCitation->Article->Abstract->AbstractText ."<br /><br /><br />"; 

        //venue
        $venue = $item->MedlineCitation->Article->Journal->Title;
        // echo $item->MedlineCitation->Article->Journal->Title ."<br /><br /><br />"; 

        //year 
         $year = $item->MedlineCitation->Article->ArticleDate->Year;
        // echo $item->MedlineCitation->Article->ArticleDate->Year ."<br /><br /><br />"; 

        $check = $item->MedlineCitation->Article->AuthorList->Author;

        if(!empty($check))
        {

                //authorlist
                foreach($item->MedlineCitation->Article->AuthorList->children() as $author){
                
                    $newAuth=[];

                    $string = $author->Identifier;
                    // echo $string."<br />";
                    $webpage="";

                    if(strpos($string,'http://orcid.org/') !== false || strpos($string,'https://orcid.org/') !== false)
                    {
                        $webpage.=$string;  
                        // echo $webpage ."<br />";
                    }
                    else if($string == "")
                    {
                        $webpage = null;
                    }
                    else
                    {
                        $webpage = 'http://orcid.org/'.$string;
                    }


                    $newAuth=[  
                            "LastName" => $author->LastName,
                            "ForeName" => $author->ForeName,
                            "WebPage" => $webpage 

                    ];

                    array_push($auth,$newAuth);//insert autori ma mancherebbe la di pubblication
                }//fine foreach autori
        }//fine if
        else
        {
            $newAuth=[  
                "LastName" => null,
                "ForeName" => null,
                "WebPage" => null 

             ];

             array_push($auth,$newAuth);
        }

        $new =[

            "title" => $title,
            "abstract" =>$abstract,
            "year" =>$year,
            "venue" => $venue,
            "auth"=>$auth
           

        ];

        return $new; // da qui in poi vanno le aggiunte dei database
        
    }//fine read article

    /**
     * @brief read the book from xmlPubmed for preview
     * 
     * @param $item
     * @return $new 
     * 
     */  
    private function readBook($item){
        $auth=[];

        //Title
        $title = $item->BookDocument->Book->BookTitle;
        //  echo $item->MedlineCitation->Article->ArticleTitle ."<br />"; 

        //abstract
        $abstract = $item->BookDocument->Abstract->AbstractText;
        //  echo $item->MedlineCitation->Article->Abstract->AbstractText ."<br /><br /><br />"; 


        //year 
         $year = $item->BookDocument->Book->PubDate->Year;
        // echo $item->MedlineCitation->Article->ArticleDate->Year ."<br /><br /><br />"; 

        //authorlist

        $check = $item->BookDocument->Book->AuthorList->Author;

        if(!empty($check))
        {
            foreach($item->BookDocument->Book->AuthorList->children() as $author){
            
                $newAuth=[];

                if(strcmp($author->children()->getName(),"CollectiveName") === 0)
                {
                       $newAuth=[  
                        "LastName" => $author->CollectiveName,
                        "ForeName" => null,
                        "WebPage" => null 
                    ];
                }
                else
                {

                    $string = $author->Identifier;
                    // echo $string."<br />";
                    $webpage="";

                    if(strpos($string,'http://orcid.org/') !== false || strpos($string,'https://orcid.org/') !== false)
                    {
                        $webpage.=$string;  
                        // echo $webpage ."<br />";
                    }
                    else if($string == "")
                    {
                        $webpage = null;
                    }
                    else
                    {
                        $webpage = 'http://orcid.org/'.$string;
                    }

                    $newAuth=[  
                            "LastName" => $author->LastName,
                            "ForeName" => $author->ForeName,
                            "WebPage" => $webpage 

                    ];

                }//fine else 

                array_push($auth,$newAuth);

        
            }//fine foreach autori

        }//fine if
        else
        {
            $newAuth=[  
                "LastName" => null,
                "ForeName" => null,
                "WebPage" => null 

             ];

             array_push($auth,$newAuth);
        }

        $new =[

            "title" => $title,
            "abstract" =>$abstract,
            "year" =>$year,
            "auth"=>$auth

        ];

        return $new;
        
    }//fine read Book

    /**
     * @brief read the article from xmlPubmed and save the data
     * 
     * read the article tag of the pubmed xml and save the data in the database
     * 
     * @param $item,$id_query
     * @return none
     * 
     */  
    private function readArticleDatabase($item,$id_query){
        $auth=[];

        //Title
        $title = $item->MedlineCitation->Article->ArticleTitle;

        $title = str_replace('[','',$title);//clean string
				
		$title = str_replace(']','',$title);//clean string
        //  echo $item->MedlineCitation->Article->ArticleTitle ."<br />"; 

        $volume = $item->MedlineCitation->Article->Journal->JournalIssue->Volume;

        $issue = $item->MedlineCitation->Article->Journal->JournalIssue->Issue;

        $pages = $item->MedlineCitation->Article->Pagination->MedlinePgn;

        //abstract
        $abstract = $item->MedlineCitation->Article->Abstract->AbstractText;
        //  echo $item->MedlineCitation->Article->Abstract->AbstractText ."<br /><br /><br />"; 

        //venue
        $nameJournal = $item->MedlineCitation->Article->Journal->Title;
        // echo $item->MedlineCitation->Article->Journal->Title ."<br /><br /><br />"; 

        //year 
        $year = $item->MedlineCitation->Article->ArticleDate->Year;
        // echo $item->MedlineCitation->Article->ArticleDate->Year ."<br /><br /><br />"; 

        //check in pubblication
        $id_pub = Pubblication::where('title','=',$title)->where('abstract','=',$abstract)->first();

        // echo json_encode($id_pub);
        
        if($id_pub === null)//is new
        {
            $id_pub = Pubblication::create([
                'title' => $title,
                'abstract' => $abstract,
                'pubblication_year' => $year,
                'pages' => null,

            ])->id;

            Journal::create([
                'id_pubblication' => $id_pub,
                'name' => $nameJournal,
                'volume' => $volume,
                'issue' => $issue,
            ]);

            Discover::create([
                'id_query' => $id_query,
                'id_pubblication' =>$id_pub,
                'source_db' => 'pubmed',
            ]);

        }
        else
        {
            $id_pub = Pubblication::where('title',$title)->where('abstract',$abstract)->first()->id;

            $id_Disco = Discover::where('id_query','=',$id_query)->where('id_pubblication','=',$id_pub)->first();

            if($id_Disco === null)
            {
                Discover::create([
                    'id_query' => $id_query,
                    'id_pubblication' =>$id_pub,
                    'source_db' => 'pubmed',
                ]);
            }
        }

        $check = $item->MedlineCitation->Article->AuthorList->Author;

        if(!empty($check))
        {

            $c = 0;$SizeTok;
            $tokV = 0;$tokP = 0;
            $country = "";

                //authorlist
                foreach($item->MedlineCitation->Article->AuthorList->children() as $author)
                {
                    $c++;

                
                    $newAuth=[];

                    $string = $author->Identifier;
                    $webpage="";

                    if(strpos($string,'http://orcid.org/') !== false || strpos($string,'https://orcid.org/') !== false)
                    {
                        $webpage.=$string;  
                        // echo $webpage ."<br />";
                    }
                    else if($string == "")
                    {
                        $webpage = null;
                    }
                    else
                    {
                        $webpage = 'http://orcid.org/'.$string;
                    }

                    $id_aut = Author::where('first_name',$author->ForeName)->where('last_name',$author->LastName)->where('web_page',$webpage)->first();

                    if($id_aut === null)
                    {
                        $id_aut = Author::create([
                            'string' => null,
                            'first_name' => $author->ForeName,
                            'last_name' => $author->LastName,
                            'email' => null,
                            'web_page' => $webpage,
                        ])->id;

                        $id_aff = Affiliation::where('string',$author->AffiliationInfo->Affiliation)->first();

                        if($id_aff === null)
                        {

                            $tokV=explode(",",$author->AffiliationInfo->Affiliation);

                            $SizeTok=count($tokV);
    
                            $tokP=explode(".",$tokV[$SizeTok-1]);
    
                            $country=$tokP[0];

                            $id_aff = Affiliation::create([
                                'string' => $author->AffiliationInfo->Affiliation,
                                'department' => null,
                                'faculty' => null,
                                'institute' => null,
                                'address' => null,
                                'city' => null,
                                'country' => $country,
                            ])->id;

                            Has::create([
                                'id_author' => $id_aut,
                                'id_affiliation' => $id_aff, 
                                'has_year' => null,
                            ]);
                        }
                        else
                        {
                            $id_aut = Author::where('first_name',$author->ForeName)->where('last_name',$author->LastName)->where('web_page',$webpage)->first()->id;
                            $id_aff = Affiliation::where('string',$author->AffiliationInfo->Affiliation)->first()->id;

                            Has::create([
                                'id_author' => $id_aut,
                                'id_affiliation' => $id_aff,
                                'has_year' => null,
                            ]);
                        }


                    }
                    else
                    {
                        $id_aut = Author::where('first_name',$author->ForeName)->where('last_name',$author->LastName)->where('web_page',$webpage)->first()->id;

                        $id_aff = Affiliation::where('string',$author->AffiliationInfo->Affiliation)->first();

                        if($id_aff === null)
                        {

                            $tokV=explode(",",$author->AffiliationInfo->Affiliation);

                            $SizeTok=count($tokV);
    
                            $tokP=explode(".",$tokV[$SizeTok-1]);
    
                            $country=$tokP[0];

                            $id_aff = Affiliation::create([
                                'string' => $author->AffiliationInfo->Affiliation,
                                'department' => null,
                                'faculty' => null,
                                'institute' => null,
                                'address' => null,
                                'city' => null,
                                'country' => $country,
                            ])->id;

                            Has::create([
                                'id_author' => $id_aut,
                                'id_affiliation' => $id_aff,
                                'has_year' => null,
                            ]);
                        }
                      
                    }

                    $id_ret = Retrive::where('id_query',$id_query)->where('id_author',$id_aut)->first();

                    if($id_ret === null )
                    {
                        Retrive::create([
                            'id_query' => $id_query,
                            'id_author' => $id_aut,
                        ]);
                    }

                    $id_write = Write::where('id_author',$id_aut)->where('id_pubblication',$id_pub)->first();

                    if($id_write === null)
                    {
                        Write::create([
                            'id_author' => $id_aut,
                            'id_pubblication' => $id_pub,
                            'author_number' => $c,
                        ]);
                    }

                   

            
                }//fine foreach autori

        }//fine if

    }//fine read article

    /**
     * @brief read the book from xmlPubmed and save the data
     * 
     * 
     * read the book tag of the pubmed xml and save the data in the database
     * 
     * @param $item,$id_query
     * @return none
     * 
     */  
    private function readBookDatabase($item,$id_query){
        $auth=[];

        //Title
        $title = $item->BookDocument->Book->BookTitle;

        $title = str_replace('[','',$title);//clean string
				
		$title = str_replace(']','',$title);//clean string
        //  echo $item->MedlineCitation->Article->ArticleTitle ."<br />"; 

        $editor = $item->BookDocument->Book->Publisher->PublisherName;

        //abstract
        $abstract = $item->BookDocument->Abstract->AbstractText;
        //  echo $item->MedlineCitation->Article->Abstract->AbstractText ."<br /><br /><br />"; 


        //year 
         $year = $item->BookDocument->Book->PubDate->Year;
        // echo $item->MedlineCitation->Article->ArticleDate->Year ."<br /><br /><br />"; 

        //authorlist

        $check = $item->BookDocument->Book->AuthorList->Author;

                //check in pubblication
        $id_pub = Pubblication::where('title',$title)->where('abstract',$abstract)->first();

        if($id_pub === null)//is new
        {
                    $id_pub = Pubblication::create([
                        'title' => $title,
                        'abstract' => $abstract,
                        'pubblication_year' => $year,
                        'pages' => null,
        
                    ])->id;
        
                    Book::create([
                        'id_pubblication' => $id_pub,
                        'book_title' => $title,
                        'editor' => $editor,
                    ]);
        
                    Discover::create([
                        'id_query' => $id_query,
                        'id_pubblication' =>$id_pub,
                        'source_db' => 'pubmed',
                    ]);
        
        }
        else
        {

                    $id_pub = Pubblication::where('title',$title)->where('abstract',$abstract)->first()->id;
                    
                    $id_Disco = Discover::where('id_query','=',$id_query)->where('id_pubblication','=',$id_pub)->first();

                    if($id_Disco === null)
                    {
                        Discover::create([
                            'id_query' => $id_query,
                            'id_pubblication' =>$id_pub,
                            'source_db' => 'pubmed',
                        ]);
                    }
        
        }

        if(!empty($check))
        {
            $c=0;

            foreach($item->BookDocument->Book->AuthorList->children() as $author){
            
                $c++;

                if(strcmp($author->children()->getName(),"CollectiveName") === 0)
                {
                    $id_aut = Author::where('first_name',$author->CollectiveName)->where('last_name',$author->LastName)->first();

                    if($id_aut === null)
                    {
                        $id_aut = Author::create([
                            'string' => null,
                            'first_name' => $author->CollectiveName,
                            'last_name' => null,
                            'email' => null,
                            'web_page' => null,
                        ])->id;

                    }

                }
                else
                {

                    $string = $author->Identifier;
                    // echo $string."<br />";
                    $webpage="";

                    if(strpos($string,'http://orcid.org/') !== false || strpos($string,'https://orcid.org/') !== false)
                    {
                        $webpage.=$string;  
                        // echo $webpage ."<br />";
                    }
                    else if($string == "")
                    {
                        $webpage = null;
                    }
                    else
                    {
                        $webpage = 'http://orcid.org/'.$string;
                    }

                }//fine else 

                $id_aut = Author::where('first_name',$author->ForeName)->where('last_name',$author->LastName)->first();

                if($id_aut === null)
                {
                    $id_aut = Author::create([
                        'string' => null,
                        'first_name' => $author->ForeName,
                        'last_name' => $author->LastName,
                        'email' => null,
                        'web_page' => $webpage,
                    ])->id;

                }
                else
                {
                    $id_aut = Author::where('first_name',$author->ForeName)->where('last_name',$author->LastName)->first()->id;    
                }

                $id_ret = Retrive::where('id_query',$id_query)->where('id_author',$id_aut)->first();

                if($id_ret === null )
                {
                    Retrive::create([
                        'id_query' => $id_query,
                        'id_author' => $id_aut,
                    ]);
                }

                $id_write = Write::where('id_author',$id_aut)->where('id_pubblication',$id_pub)->first();

                if($id_write === null)
                {
                    Write::create([
                        'id_author' => $id_aut,
                        'id_pubblication' => $id_pub,
                        'author_number' => $c,
                    ]);
                }

        
            }//fine foreach autori

        }//fine if
       
    }//fine read Book


 

    public function insertApiKey(){
        return view('query.apikey')->with('popup','open');
    }

    /**
     * @brief retrive the apikey of the logged in user 
     * 
     * save or update the apikey for pubmed or scopus through the modal "API Key"
     * 
     * @param none
     * @return $apidata
     * 
     */  
    public function saveApiKey(Request $request)
    {
        $input = $request->all();//get input

        $apiPub=$input['pubmed'];//get api key of pubmed

        $apiSco=$input['scopus']; // get api key of scopus

        //parte riguardante pubmed
        if(Access::where('id_user',Auth::user()->id)->where('id_database',1)->count() == 1)// if api key of pubmed exist
        {
            if($apiPub == null)
            {
                $apiPub="";
            }

            DB::update('update accesses set api_key = ? where id_user = ? and id_database = ?',[$apiPub,Auth::user()->id,1]);//update
        }
        else//create
        {
            Access::create([
                'id_user' => Auth::user()->id,
                'id_database' => 1,
                'api_key' => $apiPub,
            ]);
        }

        // //PARTE RIGUARDATE SCOPUS
        if(Access::where('id_user',Auth::user()->id)->where('id_database',2)->count() == 1)//if api key of scopus exist
        {

            if($apiSco == null)
            {
                $apiSco="";
            }

            DB::update('update accesses set api_key = ? where id_user = ? and id_database = ?',[$apiSco,Auth::user()->id,2]);//update 
        }
        else//create
        {
            Access::create([
                'id_user' => Auth::user()->id,
                'id_database' => 2,
                'api_key' => $apiSco,
            ]);
        }    
    }

    /**
     * @brief retrive the apikey of the logged in user 
     * 
     * @param none
     * @return $apidata
     * 
     */  
    public function getapikeyajax(){

        $apiKey = Access::where('id_user', Auth::user()->id)->get();//retrive apikey for scopus and pubmed
        
        $apidata['data'] = $apiKey;

        echo json_encode($apidata);//send data
        exit;
    }

    //never use
    public function getProgress($value) {
       return $this->load;
    }

    //never use
    public function setLoad($progress,$max) {
        $this->load=($progress/$max)*100;
    }

    /**
     * @brief method call for the train 
     * 
     * 
     * the ptrain method creates a csv file with the publications with the supervisor equal "User" 
     * and the associated label which must start from 0, once finished the csv file is passed as a parameter to the test.py file where two 
     * files with matrix will then be created confusion (confusion_matrix.txt) and accuracy (current_status.txt)
     * 
     * @param Request $request
     * @return $string
     * 
     */  
    public function ptrain(Request $request){

        set_time_limit(3600);//set time limit to 1h

        $input = $request->all();//get input

        $array_id_lab = array();

        $idQuery = $input["queryId"];//id query

        $id_q = Query::where('train_in_progress',1)->first();//search if query is in train

        if($id_q === null)//no query in train
        {
            Query::where('id',$idQuery)->update(['train_in_progress' => 1]);//set train in progress to 1
           
            $id_user = Auth::user()->id;//get user id

            $filenameCsv = '/home/mattia/medicalSearch3.0/TrainFile/'.$id_user.'_train_.csv';//train file csv

            $groupid = LabelFlag::where('id_query',$idQuery)->get();//get id label_flag of query

            $gropLabel = Label::where('id_query',$idQuery)->where('supervisor',"User")->get();//id_label of labels where supervisor "User"

            $value = 0;

            $file = fopen($filenameCsv,"w");//open file

            fputcsv($file,array('label','text'));//create label and text

            foreach($gropLabel as $label){//loop through label with user supervisor
              
                $c = 0;

                $title = Pubblication::where('id',$label->id_pubblication)->first()->title;//retrive title

                $abstract = Pubblication::where('id',$label->id_pubblication)->first()->abstract;//retrive abstract

                $line = $title.' '.$abstract;//line to add

                foreach($groupid as $id){//loop through id of label_flags where id_query = ?
              
                 if ($id->id == $label->id_labels)//if id = id_labels
                 {
                     $value = $c;
                 }

                 $c++;

                }//foreach work

                fputcsv($file,array($value,$line));//write line "value,$line(title + abstract)"

            }//foreach work

            fclose($file);//close file

            $command = escapeshellcmd("python /home/mattia/medicalSearch3.0/TrainFile/test.py  $filenameCsv");
            shell_exec($command);//execute command

            $file = fopen("/home/mattia/medicalSearch3.0/TrainFile/current_status.txt","r");//open output file
            $string = fgets($file);//get accuracy
            fclose($file);//close file

            // $file = fopen("/home/mattia/medicalSearch3.0/TrainFile/current_status.txt","r");
            // $string = fgets($file);
            // fclose($file);

            $matrix="";$rawString="";
        
            if($file = fopen("/home/mattia/medicalSearch3.0/TrainFile/confusion_matrix.txt","r"))//open output file for confusion matrix
            {
                while(!feof($file)) //to the end of file
                {
                  $rawString = trim(fgets($file));
                  if(strlen($rawString) == 0)//if length is zero means the matrix for the class is over
                  {
                    $matrix = $matrix."/";//add matrix seperator
                  }
                  else
                  {
                    $rawString = str_replace("[","",$rawString);//clean string

                    $rawString = str_replace("]","",$rawString);//clean string

                    $matrix = $matrix . $rawString." "; //matrix
                  }
                  
                } 

            }
            fclose($file);//close file

            //fine parte matrice di confusione

            $tok=explode(":",$string);//prepare string for accuracy
            $accuracy = floatval($tok[1]);//clean accuracy

            $update = [

                'train_in_progress' => 0,
                'matrix' => $matrix,
                'accuracy' => $accuracy

            ];

            Query::where('id',$idQuery)->update($update);//update query

            echo json_encode($string);//send accuracy

        }
        else
        {
            echo json_encode("esecuzione già in corso");
        }
    }

    /**
     * @brief delet all label the supervisor "User"
     * 
     * @param Request $request
     * @return none
     * 
     */  
    public function forgetlabel(Request $request){

        set_time_limit(3600);//set time limit

        $input = $request->all();//get input

        $idQuery = $input["queryId"];

        DB::table('labels')->where('id_query',$idQuery)->where('supervisor',"User")->delete();//delete query where supervisor "User"
    }


    /**
     * @brief retrive the number of label with the supervisor "User"
     * 
     * @param Request $request
     * @return $response 
     * 
     */  
    public function countlabel(Request $request){

        set_time_limit(3600);

        $input = $request->all();

        $idQuery = $input["queryId"];

        $response = Label::where('id_query',$idQuery)->where('supervisor',"User")->count();//count of the number of result

        echo json_encode($response);
    }

    /**
     * @brief retrive the accuracy of the query
     * 
     * @param Request $request
     * @return none
     * 
     */  
    public function fetchpercent(Request $request){

        $input = $request->all();

        $idQuery = $input["queryId"];

        $response = Query::where('id',$idQuery)->first()->accuracy;

        echo json_encode($response);
    }

    /**
     * @brief method call for undolabel that erases label with supervisor "AI"
     * 
     * @param Request $request
     * @return none
     * 
     * 
     */
    public function undolabel(Request $request){

        set_time_limit(3600);

        $input = $request->all();
        
        $idQuery = $input["queryId"];

        DB::table('labels')->where('id_query',$idQuery)->where('supervisor',"AI")->delete();

        Query::where('id',$idQuery)->update(['latest_exc_date' => null]);
    }

    /**
     * @brief method call to create or update the label
     * 
     * every time an association is made in the train page this method is called updating the association or creating a new one
     * 
     * @param Request $request
     * @return none
     * 
     * 
     */
    public function change(Request $request){

        set_time_limit(3600);//set time limit

        $input = $request->all();//get input

        $idQuery = $input["queryId"];//id query

        $id_pubb = $input["id_pub"];//id pubblication

        $id_lab = $input["id_lab"];//id label

        $id_l = Label::where('id_query',$idQuery)->where('id_pubblication',$id_pubb)->first();//check if exist

            if($id_l === null)//no query so create
            {
                Label::create([
                    'id_query' => $idQuery,
                    'id_labels' => $id_lab,
                    'id_pubblication' => $id_pubb,
                    'supervisor' => "User",
                ]);

                //retrive every id of Affliation
                $idAff = DB::select('select distinct has.id_affiliation from has,authors,writes,pubblications,labels where labels.id_pubblication = ? and labels.id_pubblication = pubblications.id and 
                pubblications.id = writes.id_pubblication and writes.id_author = authors.id and authors.id = has.id_author',[$id_pubb]);

                foreach($idAff as $aff){

                    $checkIdAff = Find::where('id_affialition',$aff->id_affiliation)->where('id_query',$idQuery)->first();

                    if($checkIdAff === null)
                    {
                        Find::create([
                            'id_query' => $idQuery,
                            'id_affialition' => $aff->id_affiliation,
                        ]);
                    }
                }


            }
            else//update 
            {
                $id_l = Label::where('id_query',$idQuery)->where('id_pubblication',$id_pubb)->first()->id;

                $update=[

                    'id_labels' => $id_lab,
                    'supervisor' => "User"

                ];
                
                DB::table('labels')->where('id',$id_l)->update($update);
            }
    }

    /**
     * @brief method call for apply
     * 
     * the apply method call the apply.py file with the pubblication_to_be_label file and the number of label which is named size once the function has finished opens the output file (predictions.txt) and converts the numbers from 1 to n in the labels corresponding and save the results
     * 
     * @param Request $request
     * @return none
     * 
     * 
     */
    public function apply(Request $request){

        set_time_limit(3600);//set time limiti

        $input = $request->all();//input

        $idQuery = $input["queryId"];//query id

        $id_q = Query::where('exec_in_progress',1)->first();//check if exec is progress

        if($id_q === null)//no query in train
        {

            Query::where('id',$idQuery)->update(['exec_in_progress' => 1]);//set exec in progress

            $id_user = Auth::user()->id;//retrive id user 

            $filenameCsv = '/home/mattia/medicalSearch3.0/Apply/'.$id_user.'_pubblication_to_be_labeled.csv';//prepare file

            $results = DB::select('select pubblications.* from pubblications,queries,discovers where discovers.id_pubblication = pubblications.id AND discovers.id_query = queries.id AND queries.id = ? 
            AND pubblications.id NOT IN (select labels.id_pubblication from labels where labels.supervisor = "User" AND  labels.id_query = ?) limit 100',[$idQuery,$idQuery]);

            $file = fopen($filenameCsv,"w");//open file

            fputcsv($file,array('text'));//put text in the file

            foreach ($results as $result)
            {
                $line = $result->title.' '.$result->abstract;//tile and abastrct

                fputcsv($file,array($line));//write line
            }

            fclose($file);//close file

            $size = 0;

            $groupidCicle = LabelFlag::where('id_query',$idQuery)->get();//get label_flag where equlas of id_query
            
            foreach ($groupidCicle as $idL)//count the number of label
            {
                $size++;
            }

            $command = escapeshellcmd("python /home/mattia/medicalSearch3.0/Apply/apply.py  $filenameCsv $size");
            shell_exec($command);//execute 

            $array_flag = [];

            $groupid = LabelFlag::where('id_query',$idQuery)->get();//get label_flag where equlas of id_query

            foreach($groupid as $id)
            {
                    
                array_push($array_flag,$id->id);//create a vector with inside id of the label                
   
            }//foreach work
                
            $lines = file("/home/mattia/medicalSearch3.0/Apply/predictions.txt");//retrive predictions id of the apply.py

            $c = 1;  
            $d=0;       

            foreach ($results as $result)
                {
                    $id_pubb = Pubblication::where('title',$result->title)->where('abstract',$result->abstract)->first()->id;//retrive id of the pubbliation

                    // echo json_encode($id_pubb);

                    $id_l = Label::where('id_query',$idQuery)->where('id_pubblication',$id_pubb)->first();//retrive id where id_query and id_pubbl

                    

                    if($id_l === null)//no label find so create
                    {
                        Label::create([
                            'id_query' => $idQuery,
                            'id_labels' => $array_flag[(int)($lines[$c])],
                            'id_pubblication' => $id_pubb,
                            'supervisor' => "AI",
                        ]);

                        $idAff = DB::select('select distinct has.id_affiliation from has,authors,writes,pubblications,labels where labels.id_pubblication = ? and labels.id_pubblication = pubblications.id and 
                        pubblications.id = writes.id_pubblication and writes.id_author = authors.id and authors.id = has.id_author',[$id_pubb]);
                        
                    
                        foreach($idAff as $aff){


                           $checkIdAff = Find::where('id_affialition',$aff->id_affiliation)->where('id_query',$idQuery)->first();

                           if($checkIdAff === null)
                           {

                                Find::create([
                                    'id_query' => $idQuery,
                                    'id_affialition' => $aff->id_affiliation,
                                ]);

                           }

                        }

                    }
                    else//update
                    {
                        $id_l = Label::where('id_query',$idQuery)->where('id_pubblication',$id_pubb)->first()->id;
        
                        $update=[

                            'id_labels' => $array_flag[(int)($lines[$c])],
                            'supervisor' => "AI"

                        ];
                        DB::table('labels')->where('id',$id_l)->update($update);
                    }

                    $c++;
                }

                $lastedit = date("Y-m-d");//create the date

                $update = [

                    'exec_in_progress' => 0,
                    'latest_exc_date' => $lastedit

                ];
                Query::where('id',$idQuery)->update($update);//update the query

                echo json_encode("Ok");
        }
        else
        {
            echo json_encode("esecuzione già in corso");
        }

    }

}