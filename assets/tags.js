import Tagify from '@yaireo/tagify'

var tagify = new Tagify(
    document.getElementById('tagged'), {
        originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join('; ')
    })