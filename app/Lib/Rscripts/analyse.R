# Get necessary packages
library(psy)

Analyse <- function(key, input.answers, n.answer.op) {
  n.stud <- nrow(input.answers)
  n.item <- ncol(input.answers)
  
  # Correct/Incorrect Matrix
  input.correct <- matrix(0, n.stud, n.item) 
  
  # Fill in Correct/Incorrect Matrix
  for (j in 1: ncol(input.answers)) {
    if (any(key[, j] != 0)) {  # If no key is supplied for a question, item is seen as manually graded and input.answers is used directly
      input.correct[, j] <- as.numeric(input.answers[, j] %in% which(key[, j] == 1))
    }
  }
  
  # Creating Frequency Matrix and Item rest Cor for total scores
  item.sum <- colSums(input.correct)
  item.perc <- round(item.sum / n.stud * 100, digits = 1)
  
  if (n.item > 2 & n.stud > 1) {
    item.tot.cor <- numeric()
    suppressWarnings(  # If no one or everyone answered an item correctly, R returns NA and a warning
      for (j in 1 : n.item) {
        item.tot.cor <- c(item.tot.cor,
                                    cor(input.correct[, j],
                                        apply(input.correct[, -j], 1, sum)))
      }
    )
    
    item.tot.cor[is.na(item.tot.cor)] <- 0 # Correct for when all students answered correctly or incorrectly
    item.tot.cor <- round(item.tot.cor, digits = 3)
  } else {
    item.tot.cor <- 0
  }
  
  # Creating Frequency Matrix and Item rest Cor for each answer options
  # only if any non 0's are present in key
  
  if (any(key != 0)) {
    freq.answer.op <- matrix(0, max(n.answer.op) + 1,
                             n.item)
    for (j in 1 : n.item){
      if (any(key[, j] != 0)){
        freq.answer.op[,j] <- table(factor(input.answers[, j], levels = 0:max(n.answer.op)))
      }
    }
    
    rownames <- c("Times_Answer_Missing", paste("Times", LETTERS[1:max(n.answer.op)], "answered", sep = "_"))
    rownames(freq.answer.op) <- rownames
    
    # Percentage answered per answer option per questions
    perc.answer.op <- round(freq.answer.op / n.stud * 100, digits = 1)
    
    # Calculating corrected item total correlation per Answeroptions
    if(n.item > 2 & n.stud > 1){
      answer.op.tot.cor <- matrix(0, max(n.answer.op) + 1,
                                  n.item)
      
      suppressWarnings(
        for (i in 0:max(n.answer.op)) {
          for (j in 1:n.item) {
            if (any(key[, j] != 0)) {
              answer.op.tot.cor[i + 1, j] =
                round(cor(as.numeric(input.answers[, j] == i), rowSums(input.correct[, -j])), digits = 3)
              if (is.na(answer.op.tot.cor[i + 1, j])) {
                answer.op.tot.cor[i + 1, j] <- 0
              }
            } else {
              answer.op.tot.cor[i + 1, j] <- NA
            }
          }
        }
      )
      rownames(answer.op.tot.cor) <- rownames
    } else {
      answer.op.tot.cor <- 0
    }
  }
  
  if (all(key == 0)) {
    freq.answer.op <- 0
    perc.answer.op <- 0
    answer.op.tot.cor <- 0
  }
  
  # Computes Cronbach's Alpha
  cronbach <- round(cronbach(input.correct)$alpha, digits = 3)
  
  list(cronbach, max(n.answer.op), item.sum,
       item.perc, item.tot.cor, freq.answer.op,
       perc.answer.op, answer.op.tot.cor)
  
}
