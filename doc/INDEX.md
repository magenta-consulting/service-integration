General Rules
===
- The organisation has to exist on the other side otherwise it will be skipped on this side.

CBook to Wellness
===
When:
- CBookOrganisation::linkedToWellness == true
- CBookMember::synchronisedAt <= updatedAt

What to synchronise:
- accessCode (PIN) and employeeCode
- enabled = true for activation or addition
- enabled = false for resignation
- if partnerId does not exist, delete self

How to synchronise:
0. Go over each organisation having **linkedToWellness == true** _one by one_.
1. Synchronise the organisation 
    1. with wellnessId == null.
    2. with updatedAt <= synchronisedAt
2. Synchronise Employees of an organisation
    1. Go over each employee of the organisation.
    2. Synchronise each employee 
        1. with wellnessId = null
        2. with updatedAt <= synchronisedAt
3. Synchronise the employees of the organisation on the other side.