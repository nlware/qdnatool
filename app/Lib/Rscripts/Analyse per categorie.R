setwd("//Volumes//fmg-pub//Psychologie//Onderwijsinstituut//Psychologie tweedejaarspracticumgroepen//Persoonlijke Folders//Sjoerd//qDNA Tool")
input.answers = read.table("Test.txt",sep = ",")
input.answers = input.answers[,3:22]
input.answers[input.answers == 9] <- 0
#n.answer.op <- rep(4,20)
category = sample(1:3,20,replace = TRUE)

save(results, file = "//Volumes//fmg-public//Psychologie//Onderwijsinstituut//Psychologie tweedejaarspracticumgroepen//Persoonlijke Folders//Sjoerd//qdnatool GITHUB//app//Lib//Rscripts//Random data.RData")


# Get necessary packages
library(psy)

Score <- function(key, input.answers, n.answer.op, item.names = NULL, category) {
  # Computes standard psychometric properties of an exam 
  #
  # Arguments:
  #   key: matrix of 0's and 1's. Key[i,j] implies wether answer option i to item j is right (1) or wrong (0). If a row (item) consists of
  #        only 0s, the item is interpreted as graded manually.
  #   input.answers: Ungraded matrix of answers. input.answers[i,j] is the answer of student (i) to item (j).
  #   number.answersoptions: Vector with number of answer options per item, should be equal to number of collumns of input.answers
  #   category: vector which item belongs to which category
  #
  # Returns:
  #   list with: Cronbach's alpha, maximum number of answer options, frequency and percentage correct per item, corrected item total correlation,
  #   frequence and percentage of each answer options per item, and corrected item total correlation per answer option.

  # Creat Correct/Incorrect Matrix
  if(is.null(item.names)){
    item.names <- paste("Item ",1:ncol(input.answers),sep = "")
  }
  colnames(input.answers) <- item.names
  input.correct <- input.answers
  
  # Fill in Correct/Incorrect Matrix
  for (j in 1: ncol(input.answers)) {
    if (any(key[, j] != 0)) {  # If no key is supplied for a question, item is seen as manually graded and input.answers is used directly
     input.correct[, j] <- as.numeric(input.answers[, j] %in% which(key[, j] == 1))
    }
  }

  results <- Analyse(key, input.answers, input.correct, n.answer.op)
  #If multiple categories are present, repeat the analysis for every category
  if(any(category > 1)){
    results = list(results)
    for(c in 1:max(category)){
      sel <- which(category == c)
      results <- c(results, list(Analyse(matrix(key[, sel], ,length(sel)), matrix(input.answers[, sel], ,length(sel), dimnames = list(NULL, item.names[sel])),
                                         matrix(input.correct[, sel], , length(sel), dimnames = list(NULL, item.names[sel])), n.answer.op[sel])) )
    }
  }
  return(results)
}

Analyse <- function(key, input.answers, input.correct, n.answer.op) {
  n.stud <- nrow(input.answers)
  n.item <- ncol(input.answers)
  
  # Creating Frequency Matrix and Item rest Cor for total scores
  item.sum <- colSums(input.correct)
  item.perc <- round(item.sum / n.stud * 100, digits = 1)
  
  # Calculate percentage per answer option. Only if any non 0s are present in key
  if (any(key != 0)) {
    freq.answer.op <- matrix(, max(n.answer.op) + 1, n.item)
    
    for (j in 1 : n.item){
      if (any(key[, j] != 0)){
        freq.answer.op[,j] <- table(factor(input.answers[, j], levels = 0:max(n.answer.op)))
      }
    }
    
    colnames(freq.answer.op) <- colnames(input.correct)
    rownames(freq.answer.op) <- c("Times_Answer_Missing", paste("Times", LETTERS[1:max(n.answer.op)], "answered", sep = "_"))
    
    # Percentage answered per answer option per questions
    perc.answer.op <- round(freq.answer.op / n.stud * 100, digits = 1)
    
  } else{
    freq.answer.op <- 0
    perc.answer.op <- 0
  }
      
  # Calculate corrected item tot correlation per item
  if(n.stud > 1 & n.item > 2){
    item.tot.cor <- numeric()
    suppressWarnings(  # If no one or everyone answered an item correctly, R returns NA and a warning
      for (j in 1 : n.item) {
        item.tot.cor <- c(item.tot.cor, cor(input.correct[, j], rowSums(input.correct[, -j]) ))
      }
    )
    item.tot.cor[is.na(item.tot.cor)] <- 0 # Correct for when all students answered correctly or incorrectly
    item.tot.cor <- round(item.tot.cor, digits = 3)
    names(item.tot.cor) <- colnames(input.correct)

    # Create frequency matrix and correct item total cor for each answer option
    # only if any non 0's are present in key
    if (any(key != 0)) {

      # Calculate corrected item total correlation per answer option
      answer.op.tot.cor <- matrix(, max(n.answer.op) + 1, n.item)
      
      suppressWarnings(
        for (i in 0:max(n.answer.op)) {
          for (j in 1:n.item) {
            if (any(key[, j] != 0)) {
              answer.op.tot.cor[i + 1, j] <- round(cor(as.numeric(input.answers[, j] == i), rowSums(input.correct[, -j])), 
                                                    digits = 3)
              if (is.na(answer.op.tot.cor[i + 1, j])) {
                answer.op.tot.cor[i + 1, j] <- 0
              }
            } else {
              answer.op.tot.cor[i + 1, j] <- NA
            }
          }
        }
      )

      rownames(answer.op.tot.cor) <- c("Times_Answer_Missing", paste("Times", LETTERS[1:max(n.answer.op)], "answered", sep = "_"))
      colnames(answer.op.tot.cor) <- colnames(input.correct)
      
    }

    if (all(key == 0)) {
      answer.op.tot.cor <- 0
    }
    
    # Computes Cronbach's Alpha
    cronbach <- round(cronbach(input.correct)$alpha, digits = 3)
  } else {
    cronbach <- 0
    item.tot.cor <- 0
    answer.op.tot.cor <- 0
    
  }
  
  list(n.stud = n.stud, n.item = n.item, cronbach = cronbach, item.sum = item.sum, key = key,
       n.answer.op = n.answer.op, input.correct = input.correct, #max(n.answer.op),
       item.perc = item.perc, item.tot.cor = item.tot.cor, freq.answer.op = freq.answer.op,
       perc.answer.op = perc.answer.op, answer.op.tot.cor = answer.op.tot.cor)
}

results <- Score(key, input.answers, n.answer.op, item.names = NULL, category)
save(results, file =  "//Volumes//fmg-public//Psychologie//Onderwijsinstituut//Psychologie tweedejaarspracticumgroepen//Persoonlijke Folders//Sjoerd//qdnatool GITHUB//app//Lib//Rscripts//Random data.RData")

rm(input.answers, key, category, n.answer.op, results)
