import gql from 'graphql-tag'

export const CREATE_POST_MUTATION = gql`
  mutation CreatePostMutation($input: CreatePostMutationInput!) {
    createPost(input: $input) {
      title
      text
    }
  }
`
