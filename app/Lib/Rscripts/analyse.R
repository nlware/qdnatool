# Get necessary packages
library(psy)

Analyse <- function(key, input.answers, number.answeroptions) {
  # Calculates standard psychometric properties of an exam, notably the
  # percentage correct and the item rest correlation (IRC) per item and
  # per answer option and the cronbach's alpha for the whole exam.
  #
  # Args:
  #   key: Matrix of 0's and 1's. key[i,j] implies wether answer option i
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
  #  list with:
  #   Cronbach's alpha
  #   Maximum number of answer options
  #   Vector of number of correct per item
  #   Vector of percentage correct per item
  #   Vector of IRC per item
  #   Matrix[i,j] of number of students answering option i to item j
  #     (only if any multiple choice items are present, else returns a 0)
  #   Matrix[i,j] of percentage of students answering option i to item j
  #     (only if any multiple choice items are present, else returns a 0)
  #   Matrix[i,j] of IRC of answer option i to item j
  #     (only if any multiple choice items are present, else returns a 0)

  number.students <- nrow(input.answers)
  number.questions <- ncol(input.answers)

  if (number.questions > 2 & number.students > 1) {
    # Do the analysis only if there are at least 3 items and 2 students.

    # Create Correct/Incorrect matrix
    input.correct <- matrix(0, number.students, number.questions)

    # Fill in Correct/Incorrect Matrix
    for (j in 1 : number.questions) {
      for (i in 1 : number.students) {
        if (!is.null(input.answers[i, j]) & all(key[, j] == 0)) {
          input.correct[i, j] <- input.answers[i, j]
        } else {
          if (any(input.answers[i, j] == which(key[, j] == 1))) {
             input.correct[i, j] <- 1
          }
        }
      }
    }

    # Create Frequency Matrix and Item Rest Correlation matrix for total scores
    correct.frequency <- apply(input.correct, 2, sum)
    correct.percentage <- round(correct.frequency / number.students * 100,
                                digits = 1)

    corrected.item.tot.cor <- numeric()
    suppressWarnings(
      for (j in 1 : number.questions) {
        corrected.item.tot.cor <- c(corrected.item.tot.cor,
                                    cor(input.correct[, j],
                                    apply(input.correct[, -j], 1, sum)))
      }
    )

    corrected.item.tot.cor[is.na(corrected.item.tot.cor)] <- 0
    corrected.item.tot.cor <- round(corrected.item.tot.cor, digits = 3)

    # Creating Frequency Matrix and Item rest Cor for each answer option
    # only if any non 0's are present in key, i.e. it is a multiple choice item

    if (any(key != 0)) {
      frequency.answer.options <- matrix(, max(number.answeroptions) + 1,
                                         number.questions)
      for (i in 0 : max(number.answeroptions)) {
        for (j in 1 : number.questions) {
          if (any(key[, j] != 0)) {
            frequency.answer.options[i + 1, j] <- sum(input.answers[, j] == i)
          } else {
            frequency.answer.options[i + 1, j] <- 0
          }
        }
      }

      rownames <- "Times_Answer_Missing"
      for (i in 1 : max(number.answeroptions)) {
        rownames <- c(rownames, paste(c("Times_", LETTERS[i], "_answered"),
                      collapse = ""))
      }

      rownames(frequency.answer.options) <- rownames

      # Percentage answered per answer option per questions
      percentage.answer.options <- round(frequency.answer.options / number.students * 100,
                                         digits = 1)

      # Calculating corrected item total correlation per answer option
      corrected.item.tot.cor.answ.option <- matrix(, max(number.answeroptions) + 1,
                                                   number.questions)

      suppressWarnings(
      	for (i in 0 : max(number.answeroptions)) {
          for (j in 1 : number.questions) {
            if (any(key[, j] != 0)) {
              corrected.item.tot.cor.answ.option[i + 1, j] <-
                round(cor(as.numeric(input.answers[, j] == i),
                      apply(input.correct[, -j], 1, sum)), digits = 3)
              if (is.na(corrected.item.tot.cor.answ.option[i + 1, j])) {
                corrected.item.tot.cor.answ.option[i + 1, j] <- 0
              }
            } else {
              corrected.item.tot.cor.answ.option[i + 1, j] <- NA
            }
          }
        }
      )

      rownames(corrected.item.tot.cor.answ.option) <- rownames
    }

    if (all(key == 0)) {
      frequency.answer.options <- 0
      percentage.answer.options <- 0
      corrected.item.tot.cor.answ.option <- 0
    }

    # Computes Cronbach's Alpha for overall test
    # If there is no variance in the total score returns -100
    if(!all(rowSums(input.correct) == rowSums(input.correct)[1])){
    	cronbach <- round(cronbach(input.correct)$alpha, digits = 3)
    } else {
    	cronbach <- -100
    }

    list(cronbach, max(number.answeroptions), correct.frequency,
         correct.percentage, corrected.item.tot.cor, frequency.answer.options,
         percentage.answer.options, corrected.item.tot.cor.answ.option)
  }
}
