library(psy)

Analyse <- function(key, input.answers, number.answeroptions) {
  number.students <- nrow(input.answers)
  number.questions <- ncol(input.answers)
  
  if (number.questions > 2 & number.students > 1) {
    # Correct/Incorrect Matrix
    input.correct <- matrix(0, number.students, number.questions) 
    
    # Fill in Correct/Incorrect Matrix
    for (j in 1: number.questions) {
      for (i in 1: number.students) {
        if (!is.null(input.answers[i, j]) & all(key[, j] == 0)) {
          input.correct[i, j] <- input.answers[i, j]
        } else if(any(input.answers[i, j] == which(key[, j] == 1))) {
          input.correct[i, j] <- 1
        }
      }
    }
    
    # Creating Frequency Matrix and Item rest Cor for total scores
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
    
    # Creating Frequency Matrix and Item rest Cor for each answer options
    # only if any non 0's are present in key
    
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
      for (i in 1: max(number.answeroptions)) {
        rownames <- c(rownames, paste(c("Times_", LETTERS[i], "_answered"),
                                      collapse = ""))
      } 
      
      rownames(frequency.answer.options) <- rownames
      
      # Percentage answered per answer option per questions
      percentage.answer.options <- round(frequency.answer.options / number.students * 100,
                                         digits = 1)
      
      # Calculating corrected item total correlation per Answeroptions
      corrected.item.tot.cor.answ.option <- matrix(, max(number.answeroptions) + 1,
                                                   number.questions)
      
      suppressWarnings(
        for (i in 0:max(number.answeroptions)) {
          for (j in 1:number.questions) {
            if (any(key[, j] != 0)) {
              corrected.item.tot.cor.answ.option[i + 1, j]=
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
    
    # Computes Cronbach's Alpha
    cronbach <- round(cronbach(input.correct)$alpha, digits = 3)
    
    list(cronbach, max(number.answeroptions), correct.frequency,
         correct.percentage, corrected.item.tot.cor, frequency.answer.options,
         percentage.answer.options, corrected.item.tot.cor.answ.option)
  }
}