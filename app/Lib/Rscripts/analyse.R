# Get necessary packages
library(psy)

Analyse <- function(key, input.answers, number.answeroptions) {
  # Calculates standard psychometric properties of an exam, notably the
  # percentage correct and the item rest correlation (IRC) per item and
  # per answer option and the cronbach's alpha for the whole exam.
  #
  # Args:
  #   key: Matrix of 0's and 1's. key[i,j] indicates whether answer option i
  #        to item j is right (1) or wrong (0). If a column (item) consists of
  #        only 0s, the item is interpreted as graded manually. This means
  #        that the same column of input.answers is used directly as a score
  #        (i.e. 1 is interpreted as a score of 1 instead of "answer option" 1).
  #        Number of columns (items) should be at least 3 and equal to the
  #        number of columns of input.answers and length of number.answeroptions.
  #        There is no maximum number of columns or rows
  #   input.answers: Ungraded matrix of answers. input.answers[i,j] is
  #                  the answer of student (i) to item (j). Should consist of
  #                  at least 3 columns (items) and 2 rows (students).
  #                  Number of columns should be at least 3 and equal to the
  #                  number of columns of key and length of number.answeroptions
  #                  There is no maximum.
  #   number.answersoptions: Vector with number of answer options per item.
  #                          Length should be at least 3 and equal to number of
  #                          columns of key and input.answers.
  #                          There is no maximum length.
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

  number.students <- nrow(input.answers)
  number.questions <- ncol(input.answers)

  if (number.questions <= 2 & number.students <= 1) {
    # Do the analysis only if there are at least 3 items and 2 students.
    return(FALSE)
  }

  # Create Correct/Incorrect matrix
  input.correct <- matrix(0, number.students, number.questions)

  # Fill in Correct/Incorrect Matrix
  for (j in 1 : number.questions) {
    for (i in 1 : number.students) {
      if (!is.null(input.answers[i, j]) & all(key[, j] == 0)) {
        input.correct[i, j] <- input.answers[i, j]
      } else if (any(input.answers[i, j] == which(key[, j] == 1))) {
        input.correct[i, j] <- 1
      }
    }
  }

  # Create Frequency Matrix and Item Rest Correlation matrix for total scores
  correct.frequency <- apply(input.correct, 2, sum)
  correct.percentage <- round(correct.frequency / number.students * 100,
                              digits = 1)

  corrected_item_tot_cor <- numeric()
  suppressWarnings(
    for (j in 1 : number.questions) {
      corrected_item_tot_cor <- c(corrected_item_tot_cor,
                                  cor(input.correct[, j],
                                  apply(input.correct[, -j], 1, sum)))
    }
  )

  corrected_item_tot_cor[is.na(corrected_item_tot_cor)] <- 0
  corrected_item_tot_cor <- round(corrected_item_tot_cor, digits = 3)

  # Creating Frequency Matrix and Item rest Cor for each answer option
  # only if any non 0's are present in key, i.e. it is a multiple choice item

  if (any(key != 0)) {
    frequency_answer_options <- matrix(, max(number.answeroptions) + 1,
                                       number.questions)
    for (i in 0 : max(number.answeroptions)) {
      for (j in 1 : number.questions) {
        if (any(key[, j] != 0)) {
          frequency_answer_options[i + 1, j] <- sum(input.answers[, j] == i)
        } else {
          frequency_answer_options[i + 1, j] <- 0
        }
      }
    }

    rownames <- "Times_Answer_Missing"
    for (i in 1 : max(number.answeroptions)) {
      rownames <- c(rownames, paste(c("Times_", LETTERS[i], "_answered"),
                    collapse = ""))
    }

    rownames(frequency_answer_options) <- rownames

    # Percentage answered per answer option per questions
    percentage_answer_options <- round(frequency_answer_options / number.students * 100,
                                       digits = 1)

    # Calculating corrected item total correlation per answer option
    corrected_item_tot_cor_answ_option <- matrix(, max(number.answeroptions) + 1,
                                                 number.questions)

    suppressWarnings(
      for (i in 0 : max(number.answeroptions)) {
        for (j in 1 : number.questions) {
          if (any(key[, j] != 0)) {
            corrected_item_tot_cor_answ_option[i + 1, j] <-
              round(cor(as.numeric(input.answers[, j] == i),
                    apply(input.correct[, -j], 1, sum)), digits = 3)
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
  if (!all(rowSums(input.correct) == rowSums(input.correct)[1])) {
    cronbach <- round(cronbach(input.correct)$alpha, digits = 3)
  } else {
    cronbach <- -9
  }

  list(cronbach, max(number.answeroptions), correct.frequency,
       correct.percentage, corrected_item_tot_cor, frequency_answer_options,
       percentage_answer_options, corrected_item_tot_cor_answ_option)
}
