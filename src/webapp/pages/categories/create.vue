<template>
  <b-card>
    <h1>{{ $t('common.category') }} {{ $t('common.create') }}</h1>
    <b-form @submit.stop.prevent="onSubmit">
      <b-form-row>
        <b-col md="6">
          <b-form-group
            id="input-group-label"
            label="Label"
            label-for="input-label"
          >
            <b-form-input
              id="input-label"
              v-model="form.label"
              type="text"
              placeholder="label"
              autofocus
              trim
              required
              :state="formState('label')"
            />
            <b-form-invalid-feedback :state="formState('label')">
              <ErrorsList :errors="formErrors('label')" />
            </b-form-invalid-feedback>
          </b-form-group>
        </b-col>
      </b-form-row>
      <b-button type="submit" variant="primary">
        {{ $t('common.create') }}
      </b-button>
    </b-form>
  </b-card>
</template>

<script>
import ErrorsList from '@/components/forms/ErrorsList'
import { Form } from '@/mixins/form'
import { Roles } from '@/mixins/roles'
import { Locales } from '@/mixins/locales'
import { CreateCategoryMutation } from '@/graphql/categories/create_category.mutation'
import { GlobalOverlay } from '@/mixins/global-overlay'
import { GenericToast } from '@/mixins/generic-toast'

export default {
  name: 'CategoryCreate',
  components: { ErrorsList },
  mixins: [Form, Roles, Locales, GlobalOverlay, GenericToast],
  data() {
    return {
      form: {
        label: '',
      },
    }
  },
  methods: {
    async onSubmit() {
      this.resetFormErrors()
      this.displayGlobalOverlay()

      try {
        await this.$graphql.request(CreateCategoryMutation, {
          label: this.form.label,
        })

        this.genericSuccessToast()

        this.$router.push(this.localePath({ name: 'categories' }))
      } catch (e) {
        this.hydrateFormErrors(e)
      } finally {
        this.hideGlobalOverlay()
      }
    },
  },
}
</script>
