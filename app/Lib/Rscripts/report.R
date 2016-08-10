report <- function(filename, title, number_answeroptions, key, item_names,
                   correct_frequency, correct_percentage,
                   frequency_answer_options, percentage_answer_options,
                   corrected_item_tot_cor, corrected_item_tot_cor_answ_option,
                   cronbach, student_scores, categories) {
  rmarkdown::render("report_subcategories.Rmd", output_format = "html_document",
                    output_file = paste0(filename, ".html"))
  rmarkdown::render("report_subcategories.Rmd", output_format = "pdf_document",
                    output_file = paste0(filename, ".pdf"))
}