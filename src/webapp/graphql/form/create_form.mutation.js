import { gql } from 'graphql-request'

export const CreateFormMutation = gql`
  mutation createForm(
    $firstName: String!
    $lastName: String!
    $email: String!
  ) {
    createForm(firstName: $firstName, lastName: $lastName, email: $email) {
      lastName
      firstName
      email
      user
      {
      }

    }
  }
`
