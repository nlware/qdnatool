# Packages
library(gplots)
library(ggplot2)
library(plyr)
library(gridExtra)

GenerateReport <- function(file.name, number.students, number.answeroptions,
                           number.questions, cronbach,
                           frequency.answer.options, percentage.answer.options,
                           input.correct, key, correct.frequency,
                           correct.percentage, corrected.item.tot.cor,
                           corrected.item.tot.cor.answ.option, title,
                           item.names) {
  # Creating results for each item
  item.list <- list() # Creates list to put item output in
  colnames1 <- c("Answer Option", "Frequency", "Percentage", "IRC")
  colnames2 <- c("Answer Option", "Frequency", "Percentage", "IRC", "Correct")

  # Filling a list with frequency, percentage and IRC for total and each answer options.
  # every item gets a seperate data frame
  # If no answeroptions are present, only the correct statistics are displayed.
  # If there are more than 14 answer options, only the correct statistics are displayed to conserve space.
  # This list is used for the first part of the output. This list is modified to a data frame to make the plots with the answer options.
  for (i in 1:number.questions) {
    if (number.answeroptions[i] > 0 & number.answeroptions[i] < 15) {
      Correct <- rep("Incorrect", number.answeroptions[i] + 1)
      Correct[which(key[, i] == 1)] <- "Correct"

      # Frequency is also stored at this point, but not used.
      # in case someone wants to alter the script to display the frequency instead of the percentage
      item.list[[i]] <- data.frame(c(LETTERS[1:number.answeroptions[i]], "Missing"),
                                  c(frequency.answer.options[c(2:(number.answeroptions[i] + 1), 1), i]),
                                  c(percentage.answer.options[c(2:(number.answeroptions[i] + 1), 1), i]),
                                  c(corrected.item.tot.cor.answ.option[c(2:(number.answeroptions[i] + 1), 1), i]),
                                  Correct,
                                  row.names = NULL)
      colnames(item.list[[i]]) <- colnames2
    } else {
      item.list[[i]] <- data.frame("Total", correct.frequency[i],
                                   correct.percentage[i],
                                   corrected.item.tot.cor[i])
      # Frequency is also stored at this point, but not used,
      # in case someone wants to alter the script to display the frequency instead of the percentage
      colnames(item.list[[i]]) <- colnames1
    }
  }

  items <- numeric() #Creating  item names and putting them in the list as names
  for (i in 1:number.questions) {
    items <- c(items, paste("Item", as.character(item.names[i]), sep = " "))
  }

  names(item.list) <- items

  # Create extra variables to make the bar plots
  item.list1 <- item.list
  for (i in 1:number.questions) {
    #Create right order on the x-axis (Missingness last)
    if (any(key[, i] != 0) & number.answeroptions[i] < 15) {
      item.list1[[i]]$Ans_Factor <- factor(item.list1[[i]]$"Answer Option",
                                          levels = c(LETTERS[1 : max(number.answeroptions)], "Missing"))
      item.list1[[i]]$Col_scale <- as.numeric(item.list1[[i]]$Correct) * 2 - 3
      item.list1[[i]]$IRC_col_scale <- with(item.list1[[i]], IRC * Col_scale)
      item.list1[[i]]$Perc_col_scale <- with(item.list1[[i]], Percentage)
    }
  }

  #Create data frame of all the items which have answer options. This is used to make barplots per answer option
  if (any(key != 0)) {
    AnsOpt_dataframe <- ldply(item.list1[number.answeroptions != 0 & number.answeroptions < 15], data.frame)
    names(AnsOpt_dataframe)[1] <- "id"
    AnsOpt_dataframe[, 2] <- gsub("Missing", "Mis", AnsOpt_dataframe[, 2])
    AnsOpt_dataframe$Ans_Factor <- gsub("Missing", "Mis", AnsOpt_dataframe$Ans_Factor)
    AnsOpt_dataframe$Ans_Factor <- factor(AnsOpt_dataframe$Ans_Factor,
                                          levels = c(LETTERS[1 : max(number.answeroptions)], "Mis"))
    AnsOpt_dataframe$id <- factor(AnsOpt_dataframe$id , levels = items[number.answeroptions != 0])
    AnsOpt_dataframe$Perc_col_scale[AnsOpt_dataframe$Correct == "Correct"] <- 100 - AnsOpt_dataframe$Perc_col_scale[AnsOpt_dataframe$Correct == "Correct"]

    id2 <- as.numeric(AnsOpt_dataframe$id)
    AnsOpt_dataframe <- cbind(AnsOpt_dataframe, id2)
  }

  # Create a dataframe with only the correct statistics in it. This is used in the general item plots
  dataframe_correct <- data.frame(factor(1:number.questions), correct.frequency,
                                  correct.percentage, corrected.item.tot.cor)
  names(dataframe_correct)[1] <- "item"

  # Starting explanation
  start_text <- paste(
    "Number of students  : ", number.students,"\n",
    "Number of questions : ", number.questions,"\n",
    "Average score       : ", round(mean(rowSums(input.correct)), digits = 3), "\n",
    "Standard deviation  : ", round(sd(rowSums(input.correct)), digits = 3), "\n",
    "Cronbach's alpha    : ", cronbach, "\n",
    "Standard error      : ", round(sd(rowSums(input.correct) * sqrt(1 - cronbach)), digits = 3), "\n",
    sep = "")

  explanation_items <- paste(
    "Explanation Table", "\n", "\n",
    "For each question the frequency, percentage and item rest correlations (IRC)", "\n",
    "from every answer option are diplayed. The IRC should be (highly) positive", "\n",
    "for the right answer option and low for the wrong answer option(s).", "\n",
    sep = "")

  pdf(file = file.name, h = 8, w = 10)

  # Textplot plots outside the normal plot window, therefore xpd = NA
  par(xpd = NA, mar = rep(2, 4))

  # If maximum 7 answer options 8 items per page
  if (max(number.answeroptions) < 8) {
    # Creating the first page
    layout(matrix(c(1, 1, 1, 1, 1, 1,
                    2, 2, 3, 3, 3, 3,
                    2, 2, 3, 3, 3, 3,
                    2, 2, 3, 3, 3, 3,
                    4, 4, 4, 5, 5, 5,
                    4, 4, 4, 5, 5, 5,
                    4, 4, 4, 5, 5, 5,
                    6, 6, 6, 7, 7, 7,
                    6, 6, 6, 7, 7, 7,
                    6, 6, 6, 7, 7, 7,
                    8, 8, 8, 9, 9, 9,
                    8, 8, 8, 9, 9, 9,
                    8, 8, 8, 9, 9, 9), , 6, byrow = TRUE))
    # matrix(c(1, 1, 2, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 6), 3, 6, byrow = TRUE))

    # Title
    textplot(title, valign = "top", cex = 2)

    # Add overall test info and explanation
    textplot(start_text, halign = "left", valign = "top", cex = 1, mar = c(1, 5, 5, 1))
    textplot(explanation_items, halign = "left", valign = "top", mar = c(1, 1, 5, 5))

    # Display the first 6 items
    if (number.questions < 7) {
      for (i in 1:number.questions) {
        textplot(item.list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(5, 1, 0, 1))
        title(items[i], line = 2)
        # Adding highlighting box for the right answeroption
        if (any(key[, i] == 1)) {
          for (j in 1 : sum(key[, i] == 1)) {
            rect(.19,.92 - which(key[,i] == 1)[j] * .11 , .85, 1.02 - which(key[, i] == 1)[j] * .11, col = rgb(0, .9, 0, .5), density = NA)
          }
        }
      }
    } else {
      for (i in 1:6) {
        textplot(item.list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(5, 1, 0, 1))
        title(items[i], line = 2)
        # Adding highlighting box for the right answeroption
        if (any(key[, i] == 1)) {
          for (j in 1 : sum(key[, i] == 1 )) {
            rect(.19, .92 - which(key[, i] == 1)[j] *.11 , .85, 1.02 - which(key[, i] == 1)[j] * .11, col = rgb(0, .9, 0, .5), density = NA)
          }
        }
      }

      # Creating the item output on second page forward, only if more than 6 questions
      layout(matrix(c(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 6, 7, 7, 7, 8, 8, 8), 4, 6, byrow = TRUE))
      for (i in 7:number.questions) {
        textplot(item.list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(2, 1, 3, 1))
        title(items[i], line = -0.85)

        if (any(key[, i] == 1)) {
          for (j in 1 : sum(key[, i] == 1 )) {
            rect(.19, .92 - which(key[, i] == 1)[j] * .096, .85, 1.01 - which(key[, i] == 1)[j] * .096 , col = rgb(0, .9, 0, .5), density = NA)
          }
        }
      }
    }
  } else {
    # Creates PDF for more than 7 answeroptions, only 4 items per page are displayed
    layout(matrix(c(1, 1, 1, 1, 1, 1,
                    2, 2, 3, 3, 3, 3,
                    2, 2, 3, 3, 3, 3,
                    2, 2, 3, 3, 3, 3,
                    4, 4, 4, 5, 5, 5,
                    4, 4, 4, 5, 5, 5,
                    4, 4, 4, 5, 5, 5,
                    4, 4, 4, 5, 5, 5,
                    6, 6, 6, 7, 7, 7,
                    6, 6, 6, 7, 7, 7,
                    6, 6, 6, 7, 7, 7,
                    6, 6, 6, 7, 7, 7
    ), , 6, byrow = TRUE))

    # Title
    textplot(title, valign = "top", cex = 2)

    # Introduction text
    textplot(start_text, halign = "left", valign = "top", cex = 1, mar = c(1, 5, 5, 1))
    textplot(explanation_items, halign = "left", valign = "top", mar = c(1, 1, 5, 5))

    # Creating item output
    for (i in 1:4) {
      textplot(item.list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(5, 1, 0, 1))
      title(items[i], line = 1.8)

      # Adding highlighting box for the right answeroption
      if (any(key[, i] == 1) & number.answeroptions[i] < 15) {
        for (j in 1 : sum(key[, i] == 1)) {
          rect(.19, .95 - .065 * which(key[, i] == 1)[j] , .85, 1.01 - .065*which(key[, i] == 1)[j], col = rgb(0, .9, 0, .5), density = NA)
        }
      }
    }

    # Second page and further
    layout(matrix(c(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 6), 3, 6, byrow = TRUE))

    # Displaying the items
    for (i in 4:number.questions) {
      textplot(item.list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(2, 1, 3, 1))
      title(items[i], line = -.9)

      # Adding highlighting box for the right answeroption
      if (any(key[, i] == 1) &  number.answeroptions[i] < 15) {
        for (j in 1 : sum(key[, i] == 1 )) {
          rect(.19, .95 - .065 * which(key[, i] == 1)[j] , .85, 1.01 - .065 * which(key[, i] == 1)[j], col = rgb(0, .9, 0, .5), density = NA)
        }
      }
    }
  }

  ### Frequency Plot for total items
  bar_plot_freq <- ggplot(dataframe_correct, aes(item, correct.percentage, fill = correct.percentage)) # Create chart with Answer Option on x-axis and IRC on y-asix
  bar_freq <-  bar_plot_freq + geom_bar(stat = "identity") + # Create Bar chart
    scale_fill_gradient2(low = "red", mid = "green", high = "red", midpoint = 50, limits = c(0, 100), name = "")  + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
    coord_cartesian(ylim = c(0, 100)) + # Change y-axis limit to constant
    labs(x = "Item", title = "Correct Percentage", y = "Percentage") + # Change titles and x axis name
    theme(strip.text.x = element_text(size = 7) , axis.text.x = element_text(size = 8), # Change font size of item names and Answer options
          axis.ticks.x = element_line(size = .4))

  ### IRC Bar Plot for total items
  bar_plot_IRC <- ggplot(dataframe_correct, aes(item, corrected.item.tot.cor, fill = corrected.item.tot.cor)) # Create chart with Answer_Option on x-axis and IRC on y-asix
  bar_IRC <-  bar_plot_IRC + geom_bar(stat = "identity") + # Create Bar chart
    scale_fill_gradient(low = "red", high = "green", limits = c(-.1, .4), name = "")  + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
    # coord_cartesian(ylim = c(, 1)) + # Change y-axis limit to constant
    labs(x = "Item", title = "Item Rest Correlations", y = "Item Rest Correlation") + # Change titles and x axis name
    theme(strip.text.x = element_text(size = 7), axis.text.x = element_text(size = 8), # Change font size of item names and Answer options
          axis.ticks.x = element_line(size = .4))

  # Calculating which questions are displayed on which plot.
  # Determination rule: max 80 answer options per plot
  if (any(key != 0)) {
    Tot_AnswerOptions <- 0
    Questions_p1 <- 0
    Questions_p2 <- numeric()
    Questions_p3 <- numeric()
    Questions_p4 <- numeric()
    Questions_with_AnsOpts <- nlevels(AnsOpt_dataframe$id)

    # Calculating which questions are on the first plot. Total answeroptions should be less than 100
    while(Tot_AnswerOptions < 100 & Questions_p1 < Questions_with_AnsOpts) {
      Questions_p1 <- Questions_p1 + 1
      Tot_AnswerOptions <- Tot_AnswerOptions + sum(AnsOpt_dataframe$id2 == Questions_p1)
    }

    # Calculating which questions are on the second plot. Total answeroptions should be less than 100
    Questions_p2 <- Questions_p1
    while(Tot_AnswerOptions < 200 & Questions_p2 < Questions_with_AnsOpts) {
      Questions_p2 <- Questions_p2 + 1
      Tot_AnswerOptions <- Tot_AnswerOptions + sum(AnsOpt_dataframe$id2 == Questions_p2)
    }

    # Calculating which questions are on the third plot. Total answeroptions should be less than 100
    Questions_p3 <- Questions_p2
    while(Tot_AnswerOptions < 300 & Questions_p3 < Questions_with_AnsOpts) {
      Questions_p3 <- Questions_p3 + 1
      Tot_AnswerOptions <- Tot_AnswerOptions + sum(AnsOpt_dataframe$id2 == Questions_p3)
    }

    #Emptying the plots if no questions are present for that plot
    Questions_p4 <- ifelse(Questions_p3 < Questions_with_AnsOpts, Questions_with_AnsOpts, 0)
    Questions_p3 <- ifelse(Questions_p3 != Questions_p2, Questions_p3, 0)
    Questions_p2 <- ifelse(Questions_p2 != Questions_p1, Questions_p2, 0)

    # Creating IRC plots 16 items per plot.
    # The only difference between these for codes is in the item selection on the first row
    # ([1:Questions_p1] in the first case

    bar_plot_freq1 <- ggplot(AnsOpt_dataframe[AnsOpt_dataframe$id %in% c(levels(AnsOpt_dataframe$id)[1:Questions_p1]), ]  # Create subset of first 16 questions
                            , aes("Answer Option", Percentage, fill = Correct, colour = Perc_col_scale)) # Create chart with Answer Option on x-axis and IRC on y-asix
    bar_freq1 <- bar_plot_freq1 + geom_bar(aes(x = Ans_Factor), stat = "identity") + # Create Bar chart
      facet_grid(. ~ id, scales = "free_x", space = "free_x") + # Display the different items
      scale_fill_manual(values = c("Incorrect" = "Red" ,"Correct" = "Green"), guide=FALSE) + # Fill in the bars: Green right answer options, Red wrong answer options
      scale_colour_gradient(low = "green", high = "red", guide = FALSE) + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
      coord_cartesian(ylim = c(0, 100)) + # Change y-axis limit to constant
      labs(x = "Answer Options", title = "Percentage chart(s) per question and per answer options. The green bars represent the right answer options. 
The color of the border represents the desirability (50% for the right answer options, low for the wrong answer options)") + #Change titles and x axis name
      theme(strip.text.x = element_text(size = 7) , axis.text.x = element_text(size = 4.8), # Change font size of item names and Answer options
            axis.ticks.x = element_line(size = .1), title = element_text(size = 8))

    if (Questions_p2 != 0) {
      bar_plot_freq2 <- ggplot(AnsOpt_dataframe[AnsOpt_dataframe$id %in% c(levels(AnsOpt_dataframe$id)[(Questions_p1 + 1) : Questions_p2]),] # Create subset of the other questions
                             , aes("Answer Option", Percentage, fill = Correct, colour = Perc_col_scale)) # Create chart with Answer Option on x-axis and IRC on y-asix
     bar_freq2 <-  bar_plot_freq2 + geom_bar(aes(x = Ans_Factor), stat = "identity") + # Create Bar chart
       facet_grid(. ~ id, scales = "free_x", space = "free_x") + # Display the different items
       scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) + # Fill in the bars: Green right answer options, Red wrong answer options
       scale_colour_gradient(low = "green", high = "red", guide = FALSE) + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
       coord_cartesian(ylim = c(0, 100)) + # Change y-axis limit to constant
       labs(x = "Answer Options", title = "Item") + # Change titles and x axis name
       theme(strip.text.x = element_text(size = 7), axis.text.x = element_text(size = 4.8), # Change font size of item names and Answer options
             axis.ticks.x = element_line(size = .1))
    }

    if (Questions_p3 != 0) {
      bar_plot_freq3 <- ggplot(AnsOpt_dataframe[AnsOpt_dataframe[,1] %in% c(levels(AnsOpt_dataframe$id)[(Questions_p2 + 1) : Questions_p3]),] # Create subset of the other questions
                             , aes("Answer Option", Percentage, fill = Correct, colour = Perc_col_scale)) # Create chart with Answer Option on x-axis and IRC on y-asix
     bar_freq3 <-  bar_plot_freq3 + geom_bar(aes(x = Ans_Factor), stat = "identity") + # Create Bar chart
       facet_grid(. ~ id, scales = "free_x", space = "free_x") + # Display the different items
       scale_fill_manual(values = c("Incorrect" = "Red" ,"Correct" = "Green"), guide = FALSE) + # Fill in the bars: Green right answer options, Red wrong answer options
       scale_colour_gradient(low = "green", high = "red",guide = FALSE) + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
       coord_cartesian(ylim = c(0, 100)) + # Change y-axis limit to constant
       labs(x = "Answer Options", title = "Item") + # Change titles and x axis name
       theme(strip.text.x = element_text(size = 7), axis.text.x = element_text(size = 4.8), # Change font size of item names and Answer options
             axis.ticks.x = element_line(size = .1))
    }

    if (Questions_p4 != 0) {
      bar_plot_freq4 <- ggplot(AnsOpt_dataframe[AnsOpt_dataframe[,1] %in% c(levels(AnsOpt_dataframe$id)[(Questions_p3 + 1) : Questions_p4]),] # Create subset of the other questions
                             , aes("Answer Option", Percentage, fill = Correct, colour = Perc_col_scale)) # Create chart with Answer Option on x-axis and IRC on y-asix
     bar_freq4 <-  bar_plot_freq4 + geom_bar(aes(x = Ans_Factor), stat = "identity") + # Create Bar chart
       facet_grid(. ~ id, scales = "free_x", space = "free_x") + # Display the different items
       scale_fill_manual(values = c("Incorrect" = "Red" ,"Correct" = "Green"), guide = FALSE) + # Fill in the bars: Green right answer options, Red wrong answer options
       scale_colour_gradient(low = "green", high = "red", guide = FALSE) + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
       coord_cartesian(ylim = c(0, 100)) + # Change y-axis limit to constant
       labs(x = "Answer Options", title = "Item") + # Change titles and x axis name
       theme(strip.text.x = element_text(size = 7), axis.text.x = element_text(size = 4.8), # Change font size of item names and Answer options
             axis.ticks.x = element_line(size = .1))
    }

    # Creating IRC plots 16 items per plot.
    # The only difference between these for codes is in the item selection on the first row
    # ([1:Questions_p1] in the first case)

    bar_plot_IRC1 <- ggplot(AnsOpt_dataframe[AnsOpt_dataframe$id %in% c(levels(AnsOpt_dataframe$id)[1:Questions_p1]), ]  # Create subset of first 16 questions
                           , aes("Answer Option", IRC, fill = Correct, colour = IRC_col_scale)) # Create chart with Answer Option on x-axis and IRC on y-asix
    bar_IRC1 <-  bar_plot_IRC1 + geom_bar(aes(x = Ans_Factor), stat = "identity") + # Create Bar chart
      facet_grid(. ~ id, scales = "free_x", space = "free_x") + # Display the different items
      scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) + # Fill in the bars: Green right answer options, Red wrong answer options
      scale_colour_gradient(low = "green", high = "red", guide = FALSE) + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
      coord_cartesian(ylim = c(-.3, .4)) + # Change y-axis limit to constant
      labs(x = "Answer Options", title = "IRC chart(s) per question and per answer options. The green bars represent the right answer options. 
The color of the border represents the desirability (high for the right answer options, low for the wrong answer options)") + #Change titles and x axis name
      theme(strip.text.x = element_text(size = 7), axis.text.x = element_text(size = 4.8), # Change font size of item names and Answer options
            axis.ticks.x = element_line(size = .1), title = element_text(size = 8))

    if (Questions_p2 != 0) {
      bar_plot_IRC2 <- ggplot(AnsOpt_dataframe[AnsOpt_dataframe$id %in% c(levels(AnsOpt_dataframe$id)[(Questions_p1 + 1) : Questions_p2]), ]  # Create subset of first 16 questions
                              , aes("Answer Option", IRC, fill = Correct, colour = IRC_col_scale)) # Create chart with Answer Option on x-axis and IRC on y-asix
       bar_IRC2 <-  bar_plot_IRC2 + geom_bar(aes(x = Ans_Factor), stat = "identity") + # Create Bar chart
         facet_grid(. ~ id, scales = "free_x", space = "free_x") + # Display the different items
         scale_fill_manual(values = c("Incorrect" = "Red" ,"Correct" = "Green"), guide = FALSE) + # Fill in the bars: Green right answer options, Red wrong answer options
         scale_colour_gradient(low = "green", high = "red", guide = FALSE) + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
         coord_cartesian(ylim = c(-.3,.4)) + #Change y-axis limit to constant
         labs(x="Answer Options", title = "Item") + # Change titles and x axis name
         theme(strip.text.x = element_text(size = 7) , axis.text.x = element_text(size = 4.8), # Change font size of item names and Answer options
               axis.ticks.x = element_line(size = .1))
    }

    if (Questions_p3 != 0) {
      bar_plot_IRC3 <- ggplot(AnsOpt_dataframe[AnsOpt_dataframe$id %in% c(levels(AnsOpt_dataframe$id)[(Questions_p2 + 1) : Questions_p3]), ]  # Create subset of first 16 questions
                              , aes("Answer Option", IRC, fill = Correct, colour = IRC_col_scale)) # Create chart with Answer Option on x-axis and IRC on y-asix
       bar_IRC3 <-  bar_plot_IRC3 + geom_bar(aes(x = Ans_Factor), stat = "identity") + # Create Bar chart
         facet_grid(. ~ id, scales = "free_x", space = "free_x") + # Display the different items
         scale_fill_manual(values = c("Incorrect" = "Red" ,"Correct" = "Green"), guide=FALSE) + # Fill in the bars: Green right answer options, Red wrong answer options
         scale_colour_gradient(low = "green",high = "red", guide = FALSE) + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
         coord_cartesian(ylim = c(-.3, .4)) + # Change y-axis limit to constant
         labs(x = "Answer Options", title = "Item") + # Change titles and x axis name
         theme(strip.text.x = element_text(size = 7), axis.text.x = element_text(size = 4.8), # Change font size of item names and Answer options
               axis.ticks.x = element_line(size = .1))
    }

    if (Questions_p4 != 0) {
      bar_plot_IRC4 <- ggplot(AnsOpt_dataframe[AnsOpt_dataframe$id %in% c(levels(AnsOpt_dataframe$id)[(Questions_p3 + 1) : Questions_p4]), ]  # Create subset of first 16 questions
                              , aes("Answer Option", IRC, fill = Correct, colour = IRC_col_scale)) # Create chart with Answer Option on x-axis and IRC on y-asix
       bar_IRC4 <- bar_plot_IRC4 + geom_bar(aes(x = Ans_Factor), stat = "identity") + # Create Bar chart
         facet_grid(. ~ id, scales = "free_x", space = "free_x") + # Display the different items
         scale_fill_manual(values = c("Incorrect" = "Red" ,"Correct" = "Green"), guide = FALSE) + # Fill in the bars: Green right answer options, Red wrong answer options
         scale_colour_gradient(low = "green", high = "red", guide = FALSE) + # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
         coord_cartesian(ylim = c(-.3, .4)) + # Change y-axis limit to constant
         labs(x = "Answer Options", title = "Item") + # Change titles and x axis name
         theme(strip.text.x = element_text(size = 7),
               axis.text.x = element_text(size = 4.8), # Change font size of item names and Answer options
               axis.ticks.x = element_line(size = .1))
    }
  }

  # Creating the bar plots. Depending on the amount of plots, different arranges are made.
  suppressWarnings(
    if (any(key != 0)) {
      if (Questions_p2 == 0) { # Only 1 Answer Option plot --> all plots on 1 page
        grid.newpage()
        pushViewport(viewport(layout = grid.layout(4, 1)))
        vplayout <- function(x, y)
          viewport(layout.pos.row = x, layout.pos.col = y)
        print(bar_freq, vp = vplayout(1, 1))
        print(bar_IRC, vp = vplayout(2, 1))
        print(bar_freq1, vp = vplayout(3, 1))
        print(bar_IRC1, vp = vplayout(4, 1))
      }

      if (Questions_p3 == 0 & Questions_p2 != 0) { # 2 Answer Option plots --> 2 pages of plot output
        grid.newpage()
        pushViewport(viewport(layout = grid.layout(3, 1)))
        vplayout <- function(x, y)
          viewport(layout.pos.row = x, layout.pos.col = y)
        print(bar_freq, vp = vplayout(1, 1))
        print(bar_IRC, vp = vplayout(2, 1))
        print(bar_freq1, vp = vplayout(3, 1))

        grid.newpage()
        pushViewport(viewport(layout = grid.layout(3, 1)))

        print(bar_freq2, vp = vplayout(1, 1))
        print(bar_IRC1, vp = vplayout(2, 1))
        print(bar_IRC2, vp = vplayout(3, 1))
      }

      if (Questions_p4 == 0 & Questions_p3 != 0) { # 3 Answer Option plots --> 2 pages of plot output
        grid.newpage()
        pushViewport(viewport(layout = grid.layout(2, 1)))
        vplayout <- function(x, y)
          viewport(layout.pos.row = x, layout.pos.col = y)
        print(bar_freq, vp = vplayout(1, 1))
        print(bar_IRC, vp = vplayout(2, 1))

        grid.newpage()
        pushViewport(viewport(layout = grid.layout(3, 1)))

        print(bar_freq1, vp = vplayout(1, 1))
        print(bar_freq2, vp = vplayout(2, 1))
        print(bar_freq3, vp = vplayout(3, 1))

        grid.newpage()
        pushViewport(viewport(layout = grid.layout(3, 1)))

        print(bar_IRC1, vp = vplayout(1, 1))
        print(bar_IRC2, vp = vplayout(2, 1))
        print(bar_IRC3, vp = vplayout(3, 1))
      }

      if (Questions_p4 != 0) { # 4 Answer options plots --> 3 pages of plot output
        grid.newpage()
        pushViewport(viewport(layout = grid.layout(2, 1)))
        vplayout <- function(x, y)
          viewport(layout.pos.row = x, layout.pos.col = y)
        print(bar_freq, vp = vplayout(1, 1))
        print(bar_IRC, vp = vplayout(2, 1))

        grid.newpage()
        pushViewport(viewport(layout = grid.layout(4, 1)))

        print(bar_freq1, vp = vplayout(1, 1))
        print(bar_freq2, vp = vplayout(2, 1))
        print(bar_freq3, vp = vplayout(3, 1))
        print(bar_freq4, vp = vplayout(4, 1))

        grid.newpage()
        pushViewport(viewport(layout = grid.layout(4, 1)))

        print(bar_IRC1, vp = vplayout(1, 1))
        print(bar_IRC2, vp = vplayout(2, 1))
        print(bar_IRC3, vp = vplayout(3, 1))
        print(bar_IRC4, vp = vplayout(4, 1))
      }
    } else {
      # Plot if no answer options are present
      grid.newpage()
      pushViewport(viewport(layout = grid.layout(2, 1)))
      vplayout <- function(x, y)
        viewport(layout.pos.row = x, layout.pos.col = y)
      print(bar_freq, vp = vplayout(1, 1))
      print(bar_IRC, vp = vplayout(2, 1))
    }
  )
  dev.off()
}
