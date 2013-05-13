analyse=function(key,input_answers,number_answeroptions)
{
  
  number_students=nrow(input_answers)
  number_questions=ncol(input_answers)
  
  if(number_questions > 1 & number_students > 1)
  {
    input_correct = matrix(0,number_students,number_questions)  #Correct/Incorrect Matrix 
    
    for(j in 1: number_questions)  # Fill in Correct/Incorrect Matrix
    {for(i in 1: number_students)
    {  if(!is.null(input_answers[i,j]) & all(key[,j]==0))
    {input_correct[i,j] = input_answers[i,j]}
       else
         if(any(input_answers[i,j] == which(key[,j] == 1 )))
         {input_correct[i,j]=1}
    }
    }

    
    #Get necessary packages
    
    library(psy)
    
    #Creating Frequency Matrix and Item rest Cor for total scores
    
    correct_frequency=apply(input_correct,2,sum)
    correct_percentage=round(correct_frequency/number_students*100,digits=1)
    
    corrected_item_tot_cor=numeric()
    suppressWarnings(
      for(j in 1 : number_questions)
      {corrected_item_tot_cor=c(corrected_item_tot_cor,
                                cor(input_correct[,j],apply(input_correct[,-j],1,sum)))})
    corrected_item_tot_cor[is.na(corrected_item_tot_cor)]=0
    corrected_item_tot_cor=round(corrected_item_tot_cor,digits=3)
    
    
    #Creating Frequency Matrix and Item rest Cor for each answer options
    #only if any non 0's are present in key
    
    
    if(any(key!=0))
    {
      frequency_answer_options=matrix(,max(number_answeroptions)+1,number_questions)
      for(i in 0 : max(number_answeroptions))
      {for(j in 1 : number_questions)
      {if(any(key[,j]!=0))
      {frequency_answer_options[i+1,j] = sum(input_answers[,j]==i)}
       else
         frequency_answer_options[i+1,j] = 0
      } 
      }
      
      
      rownames="Times_Answer_Missing"
      for(i in 1: max(number_answeroptions))
      {rownames = c(rownames,
                    paste(c("Times_",LETTERS[i],"_answered"),collapse=""))
      } 
      
      rownames(frequency_answer_options) = rownames
      
      
      #Percentage answered per answer option per questions
      percentage_answer_options=round(frequency_answer_options/number_students*100,digits=1)
      
      
      # Calculating corrected item total correlation
      corrected_item_tot_cor_answ_option=matrix(,max(number_answeroptions)+1,number_questions)  # Per Answeroptions
      
      suppressWarnings(
        for(i in 0:max(number_answeroptions))
        {for(j in 1:number_questions)
        {if(any(key[,j] != 0))
        {corrected_item_tot_cor_answ_option[i+1,j]=
           round(cor(as.numeric(input_answers[,j]==i),apply(input_correct[,-j],1,sum)),digits=3)
         if(is.na(corrected_item_tot_cor_answ_option[i+1,j]))
         {corrected_item_tot_cor_answ_option[i+1,j] = 0}
        }else{
          corrected_item_tot_cor_answ_option[i+1,j]=NA}
        }})
      
      rownames(corrected_item_tot_cor_answ_option) <- rownames
    }
    
    
    if(all(key==0))
    {frequency_answer_options = 0
     percentage_answer_options = 0
     corrected_item_tot_cor_answ_option = 0
    }
    
    
    # Computes Cronbach's Alpha
    Cronbach=round(cronbach(input_correct)$alpha,digits=3)
    
	list(Cronbach, max(number_answeroptions), correct_frequency, correct_percentage, corrected_item_tot_cor, frequency_answer_options, percentage_answer_options, corrected_item_tot_cor_answ_option);
  }
  
}
