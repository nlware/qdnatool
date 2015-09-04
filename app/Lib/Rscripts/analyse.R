setwd("//Volumes//fmg-public//Psychologie//Onderwijsinstituut//Psychologie tweedejaarspracticumgroepen//Persoonlijke Folders//Sjoerd//qDNA Tool")
input.answers = read.table("Test.txt",sep = ",")
input.answers = input.answers[,3:22]
input.answers = input.answers[1,1:3]
key = key[,1:3]
input.answers[input.answers == 9] <- 0
Nquest = ncol(input.answers)
n.answer.op <- rep(4,Nquest)
category = sample(1:3,Nquest,replace = TRUE)
# Test toevoeging door Sjoerd
# Get necessary packages
library(psy)

Analyse <- function(key, input.answers, n.answer.op) {
  n.stud <- nrow(input.answers)
  n.item <- ncol(input.answers)
  
  # Correct/Incorrect Matrix
  input.correct <- matrix(0, n.stud, n.item) 
  
  # Fill in Correct/Incorrect Matrix
  for (j in 1: n.item) {
    for (i in 1: n.stud) {
      if (!is.null(input.answers[i, j]) & all(key[, j] == 0)) {
        input.correct[i, j] <- input.answers[i, j]
      } else if(any(input.answers[i, j] == which(key[, j] == 1))) {
        input.correct[i, j] <- 1
      }
    }
  }
  
  # Creating Frequency Matrix and Item rest Cor for total scores
  item.sum <- apply(input.correct, 2, sum)
  item.perc <- round(item.sum / n.stud * 100, digits = 1)
  
  if (n.item > 2 & n.stud > 1) {
    corrected.item.tot.cor <- numeric()
    suppressWarnings(
      for (j in 1 : n.item) {
        corrected.item.tot.cor <- c(corrected.item.tot.cor,
                                    cor(input.correct[, j],
                                        apply(input.correct[, -j], 1, sum)))
      }
    )
    
    corrected.item.tot.cor[is.na(corrected.item.tot.cor)] <- 0
    corrected.item.tot.cor <- round(corrected.item.tot.cor, digits = 3)
  } else {
    corrected.item.tot.cor <- 0
  }
  
  # Creating Frequency Matrix and Item rest Cor for each answer options
  # only if any non 0's are present in key
  
  if (any(key != 0)) {
    freq.answer.op <- matrix(0, max(n.answer.op) + 1,
                             n.item)
    for (i in 0 : max(n.answer.op)) {
      for (j in 1 : n.item) {
        if (any(key[, j] != 0)) 
          freq.answer.op[i + 1, j] <- sum(input.answers[, j] == i)
      } 
    }
    
    rownames <- "Times_Answer_Missing"
    for (i in 1: max(n.answer.op)) {
      rownames <- c(rownames, paste(c("Times_", LETTERS[i], "_answered"),
                                    collapse = ""))
    } 
    
    rownames(freq.answer.op) <- rownames
    
    # Percentage answered per answer option per questions
    perc.answer.op <- round(freq.answer.op / n.stud * 100,
                            digits = 1)
    
    # Calculating corrected item total correlation per Answeroptions
    if(n.item > 2 & n.stud > 1){
      answer.op.tot.cor <- matrix(0, max(n.answer.op) + 1,
                                  n.item)
      
      suppressWarnings(
        for (i in 0:max(n.answer.op)) {
          for (j in 1:n.item) {
            if (any(key[, j] != 0)) {
              answer.op.tot.cor[i + 1, j]=
                round(cor(as.numeric(input.answers[, j] == i),
                          apply(input.correct[, -j], 1, sum)), digits = 3)
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
