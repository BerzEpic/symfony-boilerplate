import { gql } from 'graphql-request'

export const CreateCategoryMutation = gql`
  mutation createCategory($label: String!) {
    createCategory(label: $label) {
      id
      label
    }
  }
`
