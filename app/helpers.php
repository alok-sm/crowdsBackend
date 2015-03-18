<?php

function helper($userId)
{
  		if(isset($userId))
		{
			$result = \DB::table('task_buffers')->select('id','domain_id', 'task_id_list')->where('user_id', $userId)->orderBy('id','desc')->first();
			$task_buffer_id=$result->id;
			$domain_id=$result->domain_id;
			$task=$result->task_id_list;
			$num_task=sizeof($task);
			
			if($num_task>0){
				$index=rand(0,$num_task-1);
				$task_id=$task[$index];
				$task_desc=\DB::table('tasks')->select('id','title','type','data','answer_type','answer_data')->where('id', $task_id)->first();
				$taskId=$task_desc->id;
				$taskTitle=$task_desc->title;
				$taskType=$task_desc->type;
				$taskData=$task_desc->data;
				$answerType=$task_desc->answer_type;
				$answerData=$task_desc->answer_data;
				$task_json=(array("taskId"=>$taskId,"taskTitle"=>$taskTitle,"taskType"=>$taskType,"taskData"=>$taskData,"answerType"=>$answerType,"answerData"=>$answerData));
				$response_array=array("status"=>"success","task"=>$task_json);
				//$task=array_splice($task,$index,1);
				//DB::table('task_buffers')->where('id', $task_buffer_id)->update(['task_id_list' => $task]);
			}
			
			else{
				$domains = \DB::table('task_buffers')->select('domain_id')->where('user_id', $userId)->get();
				$domains_all=\DB::table('domains')->select('id')->get();
				$diff=array_diff($domains_all->id,$domains->domain_id);
				$size=sizeof($diff);
				$index=rand(0,$size-1);
				$domain_id=$diff[$index];
				$task=\DB::table('tasks')->select('task_id')->where('domain_id', $domain_id)->get();
				$size=sizeof($task);
				$index=rand(0,$size-1);
				$task_id=$task[$index];
				$task_desc=\DB::table('tasks')->select('id','title','type','data','answer_type','answer_data')->where('id', $task_id);
				$taskId=$task_desc->id;
				$taskTitle=$task_desc->title;
				$taskType=$task_desc->type;
				$taskData=$task_desc->data;
				$answerType=$task_desc->answer_type;
				$answerData=$task_desc->answer_data;
				$task_json=(array("taskId"=>$taskId,"taskTitle"=>$taskTitle,"taskType"=>$taskType,"taskData"=>$taskData,"answerType"=>$answerType,"answerData"=>$answerData));
				$response_array=array("status"=>"success","task"=>$task_json);
				//$task=array_splice($task,$index,1);
				
				//DB::table('task_buffers')->insert(['user_id' => $userId, 'domain_id' => $domain_id, 'task_id_list'=>$task]);
				
			}
			
		
		}
		
		else
		{
			$response_array = array('status' => 'fail');
		}
		return $response_array;
	}
?>