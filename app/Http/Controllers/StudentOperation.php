<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Result;
use App\Models\User;
use App\Models\UserExam;

class StudentOperation extends Controller
{
    //student dashboard
    public function dashboard(){

        $data['portal_exams']=Exam::select(['exams.*','exam_categories.name as cat_name'])
        ->join('exam_categories','exams.category','=','exam_categories.id')
        ->orderBy('id','desc')->where('exams.status','1')->get()->toArray();
        return view('student.dashboard',$data);
    }


    //Exam page
    public function exam(){


            $student_info = UserExam::select(['user_exams.*','users.name','exams.title','exams.exam_date'])
            ->join('users','users.id','=','user_exams.user_id')
            ->join('exams','user_exams.exam_id','=','exams.id')->orderBy('user_exams.exam_id','desc')
            ->where('user_exams.user_id',Session::get('id'))
            ->where('user_exams.std_status','1')
            ->get()->toArray();

            return view('student.exam',['student_info'=>$student_info]);

    }


    //join exam page
    public function join_exam($id){

        $question= ExamQuestion::where('exam_id',$id)->get();

        $exam=Exam::where('id',$id)->get()->first();
        return view('student.join_exam',['question'=>$question,'exam'=>$exam]);
    }



    //On submit
    public function submit_questions(Request $request){


        $yes_ans=0;
        $no_ans=0;
        $data= $request->all();
        $result=array();
        for($i=1;$i<=$request->index;$i++){

            if(isset($data['question'.$i])){
                    $q=ExamQuestion::where('id',$data['question'.$i])->get()->first();

                    if($q->ans==$data['ans'.$i]){
                        $result[$data['question'.$i]]='YES';
                        $yes_ans++;
                    }else{
                        $result[$data['question'.$i]]='NO';
                        $no_ans++;
                    }
            }
        }

       $std_info = UserExam::where('user_id',Session::get('id'))->where('exam_id',$request->exam_id)->get()->first();
       $std_info->exam_joined=1;
       $std_info->update();


       $res = new Result();
       $res->exam_id=$request->exam_id;
       $res->user_id = Session::get('id');
       $res->yes_ans=$yes_ans;
       $res->no_ans=$no_ans;
       $res->result_json=json_encode($result);

       echo $res->save();
       return redirect(url('student/exam'));
    }



    //Applying for exam
    public function apply_exam($id){

            $checkuser = UserExam::where('user_id',Session::get('id'))->where('exam_id',$id)->get()->first();

            if($checkuser){
                $arr = array('status'=>'false','message'=>'Already applied, see your exam section');
            }
            else
            {
                $exam_user = new UserExam();

                $exam_user->user_id= Session::get('id');
                $exam_user->exam_id=$id;
                $exam_user->std_status=1;
                $exam_user->exam_joined=0;

                $exam_user->save();

                $arr = array('status'=>'true','message'=>'applied successfully','reload'=>url('student/dashboard'));
            }

            echo json_encode($arr);

    }


    //View Result
    public function view_result($id){

            $data['result_info'] = Result::where('exam_id',$id)->where('user_id',Session::get('id'))->get()->first();

            $data['student_info'] = User::where('id',Session::get('id'))->get()->first();

            $data['exam_info']=Exam::where('id',$id)->get()->first();

            return view('student.view_result',$data);
    }


    //View answer
    public function view_answer($id){

        $data['question']= ExamQuestion::where('exam_id',$id)->get()->toArray();

        return view('student.view_answer',$data);
    }



}
