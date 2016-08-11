# Packages
library(gplots)
library(ggplot2)
library(plyr)
library(grid)
# nolint start
library(gridExtra)
# nolint end

report <- function(filename,
                   number_students,
                   number_answeroptions,
                   number_questions,
                   cronbach,
                   frequency_answer_options,
                   percentage_answer_options,
                   input_correct,
                   key,
                   correct_frequency,
                   correct_percentage,
                   corrected_item_tot_cor,
                   corrected_item_tot_cor_answ_option,
                   title,
                   item_names) {
  # Creating results for each item
  item_list <- list() # Creates list to put item output in
  colnames1 <- c("Answer Option", "Frequency", "Percentage", "IRC")
  colnames2 <- c("Answer Option", "Frequency", "Percentage", "IRC", "Correct")

  # Filling a list with frequency, percentage and IRC for total and each answer options.
  # every item gets a seperate data frame
  # If no answeroptions are present, only the correct statistics are displayed.
  # If there are more than 14 answer options, only the correct statistics are displayed to conserve space.
  # This list is used for the first part of the output. This list is modified to a data frame to make the plots with
  # the answer options.
  for (i in 1:number_questions) {
    if (number_answeroptions[i] > 0 & number_answeroptions[i] < 15) {
      Correct <- rep("Incorrect", number_answeroptions[i] + 1)
      Correct[which(key[, i] == 1)] <- "Correct"

      # Frequency is also stored at this point, but not used.
      # in case someone wants to alter the script to display the frequency instead of the percentage
      item_list[[i]] <- data.frame(
        c(LETTERS[1:number_answeroptions[i]], "Missing"),
        c(frequency_answer_options[c(2:(number_answeroptions[i] + 1), 1), i]),
        c(percentage_answer_options[c(2:(number_answeroptions[i] + 1), 1), i]),
        c(corrected_item_tot_cor_answ_option[c(2:(number_answeroptions[i] + 1), 1), i]),
        Correct,
        row.names = NULL
      )
      colnames(item_list[[i]]) <- colnames2
    } else {
      item_list[[i]] <- data.frame("Total", correct_frequency[i], correct_percentage[i], corrected_item_tot_cor[i])
      # Frequency is also stored at this point, but not used,
      # in case someone wants to alter the script to display the frequency instead of the percentage
      colnames(item_list[[i]]) <- colnames1
    }
  }

  #Creating  item names and putting them in the list as names
  items <- numeric()
  for (i in 1:number_questions) {
    items <- c(items, paste("Item", as.character(item_names[i]), sep = " "))
  }

  names(item_list) <- items

  # Create extra variables to make the bar plots
  item_list1 <- item_list
  for (i in 1:number_questions) {
    #Create right order on the x-axis (Missingness last)
    if (any(key[, i] != 0) & number_answeroptions[i] < 15) {
      item_list1[[i]]$Ans_Factor <- factor(
        item_list1[[i]]$"Answer Option",
        levels = c(LETTERS[1 : max(number_answeroptions)], "Missing")
      )
      # nolint start
      item_list1[[i]]$"Col_scale" <- as.numeric(item_list1[[i]]$Correct) * 2 - 3
      item_list1[[i]]$"IRC_col_scale" <- with(item_list1[[i]], IRC * Col_scale)
      item_list1[[i]]$"Perc_col_scale" <- with(item_list1[[i]], Percentage)
      # nolint end
    }
  }

  #Create data frame of all the items which have answer options. This is used to make barplots per answer option
  if (any(key != 0)) {
    ans_opt_datafrm <- plyr::ldply(item_list1[number_answeroptions != 0 & number_answeroptions < 15], data.frame)
    names(ans_opt_datafrm)[1] <- "id"
    ans_opt_datafrm[, 2] <- gsub("Missing", "Mis", ans_opt_datafrm[, 2])
    ans_opt_datafrm$Ans_Factor <- gsub("Missing", "Mis", ans_opt_datafrm$Ans_Factor)
    ans_opt_datafrm$Ans_Factor <- factor(
      ans_opt_datafrm$Ans_Factor,
      levels = c(LETTERS[1 : max(number_answeroptions)], "Mis")
    )
    ans_opt_datafrm$id <- factor(ans_opt_datafrm$id, levels = items[number_answeroptions != 0])
    ans_opt_datafrm$Perc_col_scale[ans_opt_datafrm$Correct == "Correct"] <-
      100 - ans_opt_datafrm$Perc_col_scale[ans_opt_datafrm$Correct == "Correct"]

    id2 <- as.numeric(ans_opt_datafrm$id)
    ans_opt_datafrm <- cbind(ans_opt_datafrm, id2)
  }

  # Create a dataframe with only the correct statistics in it. This is used in the general item plots
  dataframe_correct <- data.frame(
    factor(1:number_questions),
    correct_frequency,
    correct_percentage,
    corrected_item_tot_cor
  )
  names(dataframe_correct)[1] <- "item"

  # Starting explanation
  start_text <- paste(
    "Number of students  : ", number_students, "\n",
    "Number of questions : ", number_questions, "\n",
    "Average score       : ", round(mean(rowSums(input_correct)), digits = 3), "\n",
    "Standard deviation  : ", round(sd(rowSums(input_correct)), digits = 3), "\n",
    "Cronbach's alpha    : ", cronbach, "\n",
    "Standard error      : ", round(sd(rowSums(input_correct) * sqrt(1 - cronbach)), digits = 3), "\n",
    sep = ""
  )

  explanation_items <- paste(
    "Explanation Table", "\n", "\n",
    "For each question the frequency, percentage and item rest correlations (IRC)", "\n",
    "from every answer option are diplayed. The IRC should be (highly) positive", "\n",
    "for the right answer option and low for the wrong answer option(s).", "\n",
    sep = ""
  )

  pdf(file = filename, h = 8, w = 10)

  # Textplot plots outside the normal plot window, therefore xpd = NA
  par(xpd = NA, mar = rep(2, 4))

  # If maximum 7 answer options 8 items per page
  if (max(number_answeroptions) < 8) {
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
                    8, 8, 8, 9, 9, 9), ncol = 6, byrow = TRUE))
    # matrix(c(1, 1, 2, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 6), 3, 6, byrow = TRUE))

    # Title
    gplots::textplot(title, valign = "top", cex = 2)

    # Add overall test info and explanation
    gplots::textplot(start_text, halign = "left", valign = "top", cex = 1, mar = c(1, 5, 5, 1))
    gplots::textplot(explanation_items, halign = "left", valign = "top", mar = c(1, 1, 5, 5))

    # Display the first 6 items
    if (number_questions < 7) {
      for (i in 1:number_questions) {
        gplots::textplot(item_list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(5, 1, 0, 1))
        title(items[i], line = 2)
        # Adding highlighting box for the right answeroption
        if (any(key[, i] == 1)) {
          for (j in 1 : sum(key[, i] == 1)) {
            rect(
              .19,
              .92 - which(key[, i] == 1)[j] * .11,
              .85,
              1.02 - which(key[, i] == 1)[j] * .11,
              col = rgb(0, .9, 0, .5),
              density = NA
            )
          }
        }
      }
    } else {
      for (i in 1:6) {
        gplots::textplot(item_list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(5, 1, 0, 1))
        title(items[i], line = 2)
        # Adding highlighting box for the right answeroption
        if (any(key[, i] == 1)) {
          for (j in 1 : sum(key[, i] == 1)) {
            rect(
              .19,
              .92 - which(key[, i] == 1)[j] * .11,
              .85,
              1.02 - which(key[, i] == 1)[j] * .11,
              col = rgb(0, .9, 0, .5),
              density = NA
            )
          }
        }
      }

      # Creating the item output on second page forward, only if more than 6 questions
      layout(matrix(c(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 6, 7, 7, 7, 8, 8, 8), 4, 6, byrow = TRUE))
      for (i in 7:number_questions) {
        gplots::textplot(item_list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(2, 1, 3, 1))
        title(items[i], line = -0.85)

        if (any(key[, i] == 1)) {
          for (j in 1 : sum(key[, i] == 1)) {
            rect(
              .19,
              .92 - which(key[, i] == 1)[j] * .096,
              .85,
              1.01 - which(key[, i] == 1)[j] * .096,
              col = rgb(0, .9, 0, .5),
              density = NA
            )
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
                    6, 6, 6, 7, 7, 7), ncol = 6, byrow = TRUE))

    # Title
    gplots::textplot(title, valign = "top", cex = 2)

    # Introduction text
    gplots::textplot(start_text, halign = "left", valign = "top", cex = 1, mar = c(1, 5, 5, 1))
    gplots::textplot(explanation_items, halign = "left", valign = "top", mar = c(1, 1, 5, 5))

    # Creating item output
    for (i in 1:4) {
      gplots::textplot(item_list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(5, 1, 0, 1))
      title(items[i], line = 1.8)

      # Adding highlighting box for the right answeroption
      if (any(key[, i] == 1) & number_answeroptions[i] < 15) {
        for (j in 1 : sum(key[, i] == 1)) {
          rect(
            .19,
            .95 - .065 * which(key[, i] == 1)[j],
            .85,
            1.01 - .065 * which(key[, i] == 1)[j],
            col = rgb(0, .9, 0, .5),
            density = NA
          )
        }
      }
    }

    # Second page and further
    layout(matrix(c(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 6), 3, 6, byrow = TRUE))

    # Displaying the items
    for (i in 4:number_questions) {
      gplots::textplot(item_list[[i]][, 1:4], show.rownames = FALSE, cex = 1, valign = "top", mar = c(2, 1, 3, 1))
      title(items[i], line = -.9)

      # Adding highlighting box for the right answeroption
      if (any(key[, i] == 1) &  number_answeroptions[i] < 15) {
        for (j in 1 : sum(key[, i] == 1)) {
          rect(
            .19,
            .95 - .065 * which(key[, i] == 1)[j],
            .85,
            1.01 - .065 * which(key[, i] == 1)[j],
            col = rgb(0, .9, 0, .5),
            density = NA
          )
        }
      }
    }
  }

  ### Frequency Plot for total items

  # Create chart with Answer Option on x-axis and IRC on y-asix
  bar_plot_freq <- ggplot2::ggplot(
    dataframe_correct,
    ggplot2::aes(item, correct_percentage, fill = correct_percentage)
  )

  bar_freq <-
    bar_plot_freq +
    # Create Bar chart
    geom_bar(stat = "identity") +
    # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
    scale_fill_gradient2(low = "red", mid = "green", high = "red", midpoint = 50, limits = c(0, 100), name = "") +
    # Change y-axis limit to constant
    coord_cartesian(ylim = c(0, 100)) +
    # Change titles and x axis name
    labs(x = "Item", title = "Correct Percentage", y = "Percentage") +
    theme(
      strip.text.x = element_text(size = 7),
      # Change font size of item names and Answer options
      axis.text.x = element_text(size = 8),
      axis.ticks.x = element_line(size = .4)
    )

  ### IRC Bar Plot for total items

  # Create chart with Answer_Option on x-axis and IRC on y-asix
  bar_plot_IRC <- ggplot2::ggplot(
    dataframe_correct,
    ggplot2::aes(item, corrected_item_tot_cor, fill = corrected_item_tot_cor)
  )
  bar_IRC <-
    bar_plot_IRC +
    # Create Bar chart
    geom_bar(stat = "identity") +
    # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
    scale_fill_gradient(low = "red", high = "green", limits = c(-.1, .4), name = "") +
    # coord_cartesian(ylim = c(, 1)) + # Change y-axis limit to constant
    # Change titles and x axis name
    labs(x = "Item", title = "Item Rest Correlations", y = "Item Rest Correlation") +
    theme(
      strip.text.x = element_text(size = 7),
      # Change font size of item names and Answer options
      axis.text.x = element_text(size = 8),
      axis.ticks.x = element_line(size = .4)
    )

  # Calculating which questions are displayed on which plot.
  # Determination rule: max 80 answer options per plot
  if (any(key != 0)) {
    tot_answer_options <- 0
    Questions_p1 <- 0
    Questions_p2 <- numeric()
    Questions_p3 <- numeric()
    Questions_p4 <- numeric()
    questions_with_ans_opts <- nlevels(ans_opt_datafrm$id)

    # Calculating which questions are on the first plot. Total answeroptions should be less than 100
    while (tot_answer_options < 100 & Questions_p1 < questions_with_ans_opts) {
      Questions_p1 <- Questions_p1 + 1
      tot_answer_options <- tot_answer_options + sum(ans_opt_datafrm$id2 == Questions_p1)
    }

    # Calculating which questions are on the second plot. Total answeroptions should be less than 100
    Questions_p2 <- Questions_p1
    while (tot_answer_options < 200 & Questions_p2 < questions_with_ans_opts) {
      Questions_p2 <- Questions_p2 + 1
      tot_answer_options <- tot_answer_options + sum(ans_opt_datafrm$id2 == Questions_p2)
    }

    # Calculating which questions are on the third plot. Total answeroptions should be less than 100
    Questions_p3 <- Questions_p2
    while (tot_answer_options < 300 & Questions_p3 < questions_with_ans_opts) {
      Questions_p3 <- Questions_p3 + 1
      tot_answer_options <- tot_answer_options + sum(ans_opt_datafrm$id2 == Questions_p3)
    }

    # Emptying the plots if no questions are present for that plot
    Questions_p4 <- ifelse(Questions_p3 < questions_with_ans_opts, questions_with_ans_opts, 0)
    Questions_p3 <- ifelse(Questions_p3 != Questions_p2, Questions_p3, 0)
    Questions_p2 <- ifelse(Questions_p2 != Questions_p1, Questions_p2, 0)

    # Creating IRC plots 16 items per plot.
    # The only difference between these for codes is in the item selection on the first row
    # ([1:Questions_p1] in the first case

    bar_plot_freq1 <- ggplot2::ggplot(
      # Create subset of first 16 questions
      ans_opt_datafrm[ans_opt_datafrm$id %in% c(levels(ans_opt_datafrm$id)[1:Questions_p1]), ],
      # Create chart with Answer Option on x-axis and IRC on y-asix
      ggplot2::aes("Answer Option", Percentage, fill = Correct, colour = Perc_col_scale)
    )
    bar_freq1 <-
      bar_plot_freq1 +
      # Create Bar chart
      geom_bar(ggplot2::aes(x = Ans_Factor), stat = "identity") +
      # Display the different items
      facet_grid(. ~ id, scales = "free_x", space = "free_x") +
      # Fill in the bars: Green right answer options, Red wrong answer options
      scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) +
      # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
      scale_colour_gradient(low = "green", high = "red", guide = FALSE) +
      # Change y-axis limit to constant
      coord_cartesian(ylim = c(0, 100)) +
      # Change titles and x axis name
      labs(
        x = "Answer Options",
        title = paste(
          "Percentage chart(s) per question and per answer options. The green bars represent the right answer ",
          "options.",
          "\n",
          "The color of the border represents the desirability (50% for the right answer options, low for the wrong ",
          "answer options)",
          sep = ""
        )
      ) +
      theme(
        strip.text.x = element_text(size = 7),
        # Change font size of item names and Answer options
        axis.text.x = element_text(size = 4.8),
        axis.ticks.x = element_line(size = .1),
        title = element_text(size = 8)
      )

    if (Questions_p2 != 0) {
      bar_plot_freq2 <- ggplot2::ggplot(
        # Create subset of the other questions
        ans_opt_datafrm[ans_opt_datafrm$id %in% c(levels(ans_opt_datafrm$id)[(Questions_p1 + 1) : Questions_p2]), ],
        # Create chart with Answer Option on x-axis and IRC on y-asix
        ggplot2::aes("Answer Option", Percentage, fill = Correct, colour = Perc_col_scale)
      )
      bar_freq2 <-
        bar_plot_freq2 +
        # Create Bar chart
        geom_bar(ggplot2::aes(x = Ans_Factor), stat = "identity") +
        # Display the different items
        facet_grid(. ~ id, scales = "free_x", space = "free_x") +
        # Fill in the bars: Green right answer options, Red wrong answer options
        scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) +
        # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
        scale_colour_gradient(low = "green", high = "red", guide = FALSE) +
        # Change y-axis limit to constant
        coord_cartesian(ylim = c(0, 100)) +
        # Change titles and x axis name
        labs(x = "Answer Options", title = "Item") +
        theme(
          strip.text.x = element_text(size = 7),
          # Change font size of item names and Answer options
          axis.text.x = element_text(size = 4.8),
          axis.ticks.x = element_line(size = .1)
        )
    }

    if (Questions_p3 != 0) {
      bar_plot_freq3 <- ggplot2::ggplot(
        # Create subset of the other questions
        ans_opt_datafrm[ans_opt_datafrm[, 1] %in% c(levels(ans_opt_datafrm$id)[(Questions_p2 + 1) : Questions_p3]), ],
        # Create chart with Answer Option on x-axis and IRC on y-asix
        ggplot2::aes("Answer Option", Percentage, fill = Correct, colour = Perc_col_scale)
      )
      bar_freq3 <-
        bar_plot_freq3 +
        # Create Bar chart
        geom_bar(ggplot2::aes(x = Ans_Factor), stat = "identity") +
        # Display the different items
        facet_grid(. ~ id, scales = "free_x", space = "free_x") +
        # Fill in the bars: Green right answer options, Red wrong answer options
        scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) +
        # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
        scale_colour_gradient(low = "green", high = "red", guide = FALSE) +
        # Change y-axis limit to constant
        coord_cartesian(ylim = c(0, 100)) +
        # Change titles and x axis name
        labs(x = "Answer Options", title = "Item") +
        theme(
          strip.text.x = element_text(size = 7),
          # Change font size of item names and Answer options
          axis.text.x = element_text(size = 4.8),
          axis.ticks.x = element_line(size = .1)
        )
    }

    if (Questions_p4 != 0) {
      bar_plot_freq4 <- ggplot2::ggplot(
        # Create subset of the other questions
        ans_opt_datafrm[ans_opt_datafrm[, 1] %in% c(levels(ans_opt_datafrm$id)[(Questions_p3 + 1) : Questions_p4]), ],
        # Create chart with Answer Option on x-axis and IRC on y-asix
        ggplot2::aes("Answer Option", Percentage, fill = Correct, colour = Perc_col_scale)
      )
      bar_freq4 <-
        bar_plot_freq4 +
        # Create Bar chart
        geom_bar(ggplot2::aes(x = Ans_Factor), stat = "identity") +
        # Display the different items
        facet_grid(. ~ id, scales = "free_x", space = "free_x") +
        # Fill in the bars: Green right answer options, Red wrong answer options
        scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) +
        # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
        scale_colour_gradient(low = "green", high = "red", guide = FALSE) +
        # Change y-axis limit to constant
        coord_cartesian(ylim = c(0, 100)) +
        # Change titles and x axis name
        labs(x = "Answer Options", title = "Item") +
        theme(
          strip.text.x = element_text(size = 7),
          # Change font size of item names and Answer options
          axis.text.x = element_text(size = 4.8),
          axis.ticks.x = element_line(size = .1)
        )
    }

    # Creating IRC plots 16 items per plot.
    # The only difference between these for codes is in the item selection on the first row
    # ([1:Questions_p1] in the first case)

    bar_plot_IRC1 <- ggplot2::ggplot(
      # Create subset of first 16 questions
      ans_opt_datafrm[ans_opt_datafrm$id %in% c(levels(ans_opt_datafrm$id)[1:Questions_p1]), ],
      # Create chart with Answer Option on x-axis and IRC on y-asix
      ggplot2::aes("Answer Option", IRC, fill = Correct, colour = IRC_col_scale)
    )
    bar_IRC1 <-
      bar_plot_IRC1 +
      # Create Bar chart
      geom_bar(ggplot2::aes(x = Ans_Factor), stat = "identity") +
      # Display the different items
      facet_grid(. ~ id, scales = "free_x", space = "free_x") +
      # Fill in the bars: Green right answer options, Red wrong answer options
      scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) +
      # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
      scale_colour_gradient(low = "green", high = "red", guide = FALSE) +
      # Change y-axis limit to constant
      coord_cartesian(ylim = c(-.3, .4)) +
      # Change titles and x axis name
      labs(
        x = "Answer Options",
        title = paste(
          "IRC chart(s) per question and per answer options. The green bars represent the right answer options.",
          "\n",
          "The color of the border represents the desirability (high for the right answer options, low for the ",
          "wrong answer options)",
          sep = ""
        )
      ) +
      theme(
        strip.text.x = element_text(size = 7),
        # Change font size of item names and Answer options
        axis.text.x = element_text(size = 4.8),
        axis.ticks.x = element_line(size = .1),
        title = element_text(size = 8)
      )

    if (Questions_p2 != 0) {
      bar_plot_IRC2 <- ggplot2::ggplot(
        # Create subset of first 16 questions
        ans_opt_datafrm[ans_opt_datafrm$id %in% c(levels(ans_opt_datafrm$id)[(Questions_p1 + 1) : Questions_p2]), ],
        # Create chart with Answer Option on x-axis and IRC on y-asix
        ggplot2::aes("Answer Option", IRC, fill = Correct, colour = IRC_col_scale)
      )
      bar_IRC2 <-
        bar_plot_IRC2 +
        # Create Bar chart
        geom_bar(ggplot2::aes(x = Ans_Factor), stat = "identity") +
        # Display the different items
        facet_grid(. ~ id, scales = "free_x", space = "free_x") +
        # Fill in the bars: Green right answer options, Red wrong answer options
        scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) +
        # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
        scale_colour_gradient(low = "green", high = "red", guide = FALSE) +
        # Change y-axis limit to constant
        coord_cartesian(ylim = c(-.3, .4)) +
        # Change titles and x axis name
        labs(x = "Answer Options", title = "Item") +
        theme(
          strip.text.x = element_text(size = 7),
          # Change font size of item names and Answer options
          axis.text.x = element_text(size = 4.8),
          axis.ticks.x = element_line(size = .1)
        )
    }

    if (Questions_p3 != 0) {
      bar_plot_IRC3 <- ggplot2::ggplot(
        # Create subset of first 16 questions
        ans_opt_datafrm[ans_opt_datafrm$id %in% c(levels(ans_opt_datafrm$id)[(Questions_p2 + 1) : Questions_p3]), ],
        # Create chart with Answer Option on x-axis and IRC on y-asix
        ggplot2::aes("Answer Option", IRC, fill = Correct, colour = IRC_col_scale)
      )
      bar_IRC3 <-
        bar_plot_IRC3 +
        # Create Bar chart
        geom_bar(ggplot2::aes(x = Ans_Factor), stat = "identity") +
        # Display the different items
        facet_grid(. ~ id, scales = "free_x", space = "free_x") +
        # Fill in the bars: Green right answer options, Red wrong answer options
        scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) +
        # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
        scale_colour_gradient(low = "green", high = "red", guide = FALSE) +
        # Change y-axis limit to constant
        coord_cartesian(ylim = c(-.3, .4)) +
        # Change titles and x axis name
        labs(x = "Answer Options", title = "Item") +
        theme(
          strip.text.x = element_text(size = 7),
          # Change font size of item names and Answer options
          axis.text.x = element_text(size = 4.8),
          axis.ticks.x = element_line(size = .1)
        )
    }

    if (Questions_p4 != 0) {
      bar_plot_IRC4 <- ggplot2::ggplot(
        # Create subset of first 16 questions
        ans_opt_datafrm[ans_opt_datafrm$id %in% c(levels(ans_opt_datafrm$id)[(Questions_p3 + 1) : Questions_p4]), ],
        # Create chart with Answer Option on x-axis and IRC on y-asix
        ggplot2::aes("Answer Option", IRC, fill = Correct, colour = IRC_col_scale)
      )
      bar_IRC4 <-
        bar_plot_IRC4 +
        # Create Bar chart
        geom_bar(ggplot2::aes(x = Ans_Factor), stat = "identity") +
        # Display the different items
        facet_grid(. ~ id, scales = "free_x", space = "free_x") +
        # Fill in the bars: Green right answer options, Red wrong answer options
        scale_fill_manual(values = c("Incorrect" = "Red", "Correct" = "Green"), guide = FALSE) +
        # Create colour boundray: Green = "right" (low for wrong answer options, high for right answer options)
        scale_colour_gradient(low = "green", high = "red", guide = FALSE) +
        # Change y-axis limit to constant
        coord_cartesian(ylim = c(-.3, .4)) +
        # Change titles and x axis name
        labs(x = "Answer Options", title = "Item") +
        theme(
          strip.text.x = element_text(size = 7),
          axis.text.x = element_text(size = 4.8), # Change font size of item names and Answer options
          axis.ticks.x = element_line(size = .1)
        )
    }
  }

  # Creating the bar plots. Depending on the amount of plots, different arranges are made.
  suppressWarnings(
    if (any(key != 0)) {
      if (Questions_p2 == 0) {
        # Only 1 Answer Option plot --> all plots on 1 page

        grid::grid.newpage()
        grid::pushViewport(grid::viewport(layout = grid::grid.layout(4, 1)))
        vplayout <- function(x, y) {
          grid::viewport(layout.pos.row = x, layout.pos.col = y)
        }
        print(bar_freq, vp = vplayout(1, 1))
        print(bar_IRC, vp = vplayout(2, 1))
        print(bar_freq1, vp = vplayout(3, 1))
        print(bar_IRC1, vp = vplayout(4, 1))
      }

      if (Questions_p3 == 0 & Questions_p2 != 0) {
        # 2 Answer Option plots --> 2 pages of plot output

        grid::grid.newpage()
        grid::pushViewport(grid::viewport(layout = grid::grid.layout(3, 1)))
        vplayout <- function(x, y) {
          grid::viewport(layout.pos.row = x, layout.pos.col = y)
        }
        print(bar_freq, vp = vplayout(1, 1))
        print(bar_IRC, vp = vplayout(2, 1))
        print(bar_freq1, vp = vplayout(3, 1))

        grid::grid.newpage()
        grid::pushViewport(grid::viewport(layout = grid::grid.layout(3, 1)))

        print(bar_freq2, vp = vplayout(1, 1))
        print(bar_IRC1, vp = vplayout(2, 1))
        print(bar_IRC2, vp = vplayout(3, 1))
      }

      if (Questions_p4 == 0 & Questions_p3 != 0) {
        # 3 Answer Option plots --> 2 pages of plot output

        grid::grid.newpage()
        grid::pushViewport(grid::viewport(layout = grid::grid.layout(2, 1)))
        vplayout <- function(x, y) {
          grid::viewport(layout.pos.row = x, layout.pos.col = y)
        }
        print(bar_freq, vp = vplayout(1, 1))
        print(bar_IRC, vp = vplayout(2, 1))

        grid::grid.newpage()
        grid::pushViewport(grid::viewport(layout = grid::grid.layout(3, 1)))

        print(bar_freq1, vp = vplayout(1, 1))
        print(bar_freq2, vp = vplayout(2, 1))
        print(bar_freq3, vp = vplayout(3, 1))

        grid::grid.newpage()
        grid::pushViewport(grid::viewport(layout = grid::grid.layout(3, 1)))

        print(bar_IRC1, vp = vplayout(1, 1))
        print(bar_IRC2, vp = vplayout(2, 1))
        print(bar_IRC3, vp = vplayout(3, 1))
      }

      if (Questions_p4 != 0) {
        # 4 Answer options plots --> 3 pages of plot output

        grid::grid.newpage()
        grid::pushViewport(grid::viewport(layout = grid::grid.layout(2, 1)))
        vplayout <- function(x, y) {
          grid::viewport(layout.pos.row = x, layout.pos.col = y)
        }
        print(bar_freq, vp = vplayout(1, 1))
        print(bar_IRC, vp = vplayout(2, 1))

        grid::grid.newpage()
        grid::pushViewport(grid::viewport(layout = grid::grid.layout(4, 1)))

        print(bar_freq1, vp = vplayout(1, 1))
        print(bar_freq2, vp = vplayout(2, 1))
        print(bar_freq3, vp = vplayout(3, 1))
        print(bar_freq4, vp = vplayout(4, 1))

        grid::grid.newpage()
        grid::pushViewport(grid::viewport(layout = grid::grid.layout(4, 1)))

        print(bar_IRC1, vp = vplayout(1, 1))
        print(bar_IRC2, vp = vplayout(2, 1))
        print(bar_IRC3, vp = vplayout(3, 1))
        print(bar_IRC4, vp = vplayout(4, 1))
      }
    } else {
      # Plot if no answer options are present

      grid::grid.newpage()
      grid::pushViewport(grid::viewport(layout = grid::grid.layout(2, 1)))
      vplayout <- function(x, y) {
        grid::viewport(layout.pos.row = x, layout.pos.col = y)
      }
      print(bar_freq, vp = vplayout(1, 1))
      print(bar_IRC, vp = vplayout(2, 1))
    }
  )
  dev.off()
}
