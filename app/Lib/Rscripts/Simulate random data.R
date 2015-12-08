#### Simulate data. Variables which are both used in the analysis and the simulation are name differently, 
#### to make sure the analysis script is indepedent of the simulation results/input.

number.items = 25                       # Number of Questions
number.students = 50                    # Number of Students
#n.answer.op = c(3)                     # Number of answeroptions per item
n.answer.op = rep(0 ,number.items)
#n.answer.op = sample(c(0,2:6), number.items, replace=TRUE) #Give each item a random max. of answer options.

category = sample(1:3, number.items, replace = TRUE, prob = c(.5,.3,.2))  # Define categories
#category = c(rep(1,10))


key1 = numeric()                           # Generates Key, 0 = no key, assumed these are self graded answers
for(i in 1:number.items) {
  if(n.answer.op[i] == 0) {
    key1[i] = 0 
  } else { 
    key1[i] = sample(1:n.answer.op[i], 1)}  #Pick one good answer option.
}

key = matrix(0, max(n.answer.op), number.items) #Create key
for(i in 1: number.items)
  key[key1[i],i] = 1

if(nrow(key) == 0)
  key = matrix(0, 1, number.items)

##Create extra right answer options

#key0 = matrix(sample(c(1,0), number.items * max(n.answer.op), replace=TRUE, prob=c(.1,.9)),
#              max(n.answer.op), number.items)  #Create extra "right" answer options.
#key = key + key0
#key = ifelse(key > 1, 1, key) # 2 become 1.


for(i in 1:number.items) {
  if(n.answer.op[i] < max(n.answer.op)) # Make sure an answer option a question does not have is graded as correctly.
    key[(1 + n.answer.op[i]) : max(n.answer.op),i] = 0
}

input.answers = matrix(, number.students, number.items) #Generates given answers. Totally random
for(j in 1:number.items) {
  if (n.answer.op[j] == 0) {                     #If no answer options are given, either good (1) or false (0)
    input.answers[,j] = sample(0 : 1, number.students, TRUE)
  } else {
    input.answers[,j] = sample(1 : n.answer.op[j], number.students, TRUE)}
}

missing = matrix(sample(c(1,0), number.items * number.students, replace=TRUE, prob=c(.99,.01)),
                 number.students, number.items)
input.answers = input.answers * missing         

rm(key0, missing, i, j, key1, number.items, number.students)
