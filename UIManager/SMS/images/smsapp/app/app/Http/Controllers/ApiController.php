<?php

/**
 * SMS API
 * Farmerline SMS API
 *
 * OpenAPI spec version: 1.0.0
 * 
 *
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use App\Guzzle;
use App\Application;
use Log;

class ApiController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation smsSendPost
     *
     * Send SMS.
     *
     *
     * @return Http response
     */
    public function smsSendPost(Request $request)
    {
        $input = $request->all();

        //path params validation


        //not path params validation
        
       $response = Guzzle::send('post' , 'https://messaging.mergdata.com/api/v1/sms/send' , $input  ,  ['Api-Token' => '318b0ca1-c1ea-445c-9db7-ae7a886d4cd9']);


        return response()->json( $response );
    }


     public function register( Request $request ){

        try{


        $input = $request->all();


        $app = Application::create($input);


        return response()->json( ['success'=> true , 'message' =>'Application successfully created' , 'app_id'=> $app->id ]);

    }

    catch( Execption $e){

        return response()->json( ['success'=> false , 'message' =>'Error whiles creating application' , 'error'=> $e->getMessage() ]);
    }

    }


    public function incoming( Request $request ){

        $input = $request->all();

        $smsContent = $input['Text'] ;

        $smsArray = explode( ' ', trim( $input['Text']));

        if( count( $smsArray) >=2){


        $application = Application::whereKeyword( $smsArray[0] )->first();


        if( $application) {
             Guzzle::send('post' , $application->url , array_only( $input , ['Text' , 'From'])  );

            return response()->json( ['success'=> true , 'message' =>"Message forwarded to $application->url" , 'app_id'=> $app->id ]);
        }
        else{

        return response()->json( ['success'=> false , 'message' =>'Application not found'  ]);
        }

        }
        else{

        return response()->json( ['success'=> false , 'message' =>'Sms must have a content with the keyword as the first word in the sentence'  ]);
        }

        
    }
}