# Get necessary packages
library(psy)

analyse <- function(key, input_answers, number_answeroptions) {
  # Calculates standard psychometric properties of an exam, notably the percentage correct and the item rest
  # correlation (IRC) per item and per answer option and the cronbach's alpha for the whole exam.
  #
  # Args:
  #   key: Matrix of 0's and 1's. key[i,j] indicates whether answer option i to item j is right (1) or wrong (0).
  #        If a column (item) consists of only 0s, the item is interpreted as graded manually. This means that the
  #        same column of input_answers is used directly as a score (i.e. 1 is interpreted as a score of 1 instead
  #        of "answer option" 1).
  #        Number of columns (items) should be at least 3 and equal to the number of columns of input_answers and
  #        length of number_answeroptions. There is no maximum number of columns or rows
  #   input_answers: Ungraded matrix of answers. input_answers[i,j] is the answer of student (i) to item (j).
  #                  Should consist of at least 3 columns (items) and 2 rows (students). Number of columns should be
  #                  at least 3 and equal to the number of columns of key and length of number_answeroptions.
  #                  There is no maximum.
  #   number_answeroptions: Vector with number of answer options per item.
  #                         Length should be at least 3 and equal to number of columns of key and input_answers.
  #                         There is no maximum length.
  #
  # Returns:
  #   a list containing:
  #     Cronbach's alpha
  #     Maximum number of answer options
  #     Vector of number of correct per item
  #     Vector of percentage correct per item
  #     Vector of IRC per item
  #     Matrix[i,j] of number of students answering option i to item j
  #       (only if any multiple choice items are present, else returns a 0)
  #     Matrix[i,j] of percentage of students answering option i to item j
  #       (only if any multiple choice items are present, else returns a 0)
  #     Matrix[i,j] of IRC of answer option i to item j
  #       (only if any multiple choice items are present, else returns a 0)
  #   on success, or FALSE on failure,

  number_students <- nrow(input_answers)
  number_questions <- ncol(input_answers)

  if (number_questions <= 2 & number_students <= 1) {
    # Do the analysis only if there are at least 3 items and 2 students.
    return(FALSE)
  }

  # Create Correct/Incorrect matrix
  input_correct <- matrix(0, number_students, number_questions)

  # Fill in Correct/Incorrect Matrix
  for (j in 1 : number_questions) {
    for (i in 1 : number_students) {
      if (!is.null(input_answers[i, j]) & all(key[, j] == 0)) {
        input_correct[i, j] <- input_answers[i, j]
      } else if (any(input_answers[i, j] == which(key[, j] == 1))) {
        input_correct[i, j] <- 1
      }
    }
  }

  # Create Frequency Matrix and Item Rest Correlation matrix for total scores
  correct_frequency <- apply(input_correct, 2, sum)
  correct_percentage <- round(correct_frequency / number_students * 100, digits = 1)

  corrected_item_tot_cor <- numeric()
  suppressWarnings(
    for (j in 1 : number_questions) {
      corrected_item_tot_cor <- c(corrected_item_tot_cor, cor(input_correct[, j], apply(input_correct[, -j], 1, sum)))
    }
  )

  corrected_item_tot_cor[is.na(corrected_item_tot_cor)] <- 0
  corrected_item_tot_cor <- round(corrected_item_tot_cor, digits = 3)

  # Creating Frequency Matrix and Item rest Cor for each answer option
  # only if any non 0's are present in key, i.e. it is a multiple choice item

  if (any(key != 0)) {
    frequency_answer_options <- matrix(, max(number_answeroptions) + 1, number_questions)
    for (i in 0 : max(number_answeroptions)) {
      for (j in 1 : number_questions) {
        if (any(key[, j] != 0)) {
          frequency_answer_options[i + 1, j] <- sum(input_answers[, j] == i)
        } else {
          frequency_answer_options[i + 1, j] <- 0
        }
      }
    }

    rownames <- "Times_Answer_Missing"
    for (i in 1 : max(number_answeroptions)) {
      rownames <- c(rownames, paste(c("Times_", LETTERS[i], "_answered"), collapse = ""))
    }

    rownames(frequency_answer_options) <- rownames

    # Percentage answered per answer option per questions
    percentage_answer_options <- round(frequency_answer_options / number_students * 100, digits = 1)

    # Calculating corrected item total correlation per answer option
    corrected_item_tot_cor_answ_option <- matrix(, max(number_answeroptions) + 1, number_questions)

    suppressWarnings(
      for (i in 0 : max(number_answeroptions)) {
        for (j in 1 : number_questions) {
          if (any(key[, j] != 0)) {
            corrected_item_tot_cor_answ_option[i + 1, j] <- round(
              cor(as.numeric(input_answers[, j] == i), apply(input_correct[, -j], 1, sum)),
              digits = 3
            )
            if (is.na(corrected_item_tot_cor_answ_option[i + 1, j])) {
              corrected_item_tot_cor_answ_option[i + 1, j] <- 0
            }
          } else {
            corrected_item_tot_cor_answ_option[i + 1, j] <- NA
          }
        }
      }
    )

    rownames(corrected_item_tot_cor_answ_option) <- rownames
  }

  if (all(key == 0)) {
    frequency_answer_options <- 0
    percentage_answer_options <- 0
    corrected_item_tot_cor_answ_option <- 0
  }

  # Computes Cronbach's Alpha for overall test
  # If there is no variance in the total score returns -100
  if (!all(rowSums(input_correct) == rowSums(input_correct)[1])) {
    cronbach <- round(cronbach(input_correct)$alpha, digits = 3)
  } else {
    cronbach <- -9
  }

  list(
    cronbach, max(number_answeroptions), correct_frequency, correct_percentage, corrected_item_tot_cor,
    frequency_answer_options, percentage_answer_options, corrected_item_tot_cor_answ_option
  )
}
