import { gql } from 'graphql-request'

export const DeleteCategoryMutation = gql`
  mutation deleteCategory($id: Int!) {
    deleteCategory(category1: { id: $id })
  }
`
