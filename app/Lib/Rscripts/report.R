report <- function(filename,
                   number_answeroptions,
                   cronbach,
                   frequency_answer_options,
                   percentage_answer_options,
                   key,
                   correct_frequency,
                   correct_percentage,
                   corrected_item_tot_cor,
                   corrected_item_tot_cor_answ_option,
                   title,
                   item_names,
                   student_scores,
                   categories) {
  rmarkdown::render("/home/travis/build/nlware/qdnatool/app/Lib/Rscripts/report.Rmd", output_format = "pdf_document", output_file = filename)
}
