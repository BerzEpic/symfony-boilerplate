import { gql } from 'graphql-request'

export const UpdateCategoryMutation = gql`
  mutation updateCategory($id: Int!, $label: String!) {
    updateCategory(category: { id: $id }, label: $label) {
      id
      label
    }
  }
`
