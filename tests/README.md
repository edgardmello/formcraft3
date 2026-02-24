# FormCraft Logic Tests

Testes unitários para os operadores de lógica condicional do FormCraft.

## Instalação

```bash
cd tests
npm install
```

## Executar Testes

```bash
# Executar todos os testes
npm test

# Executar em modo headed (ver browser)
npm run test:headed

# Executar com interface UI
npm run test:ui

# Ver relatório HTML
npm run test:report
```

## Operadores Testados

### Operadores Originais (Regression Tests)
- `equal_to` - Igual a
- `not_equal_to` - Diferente de
- `contains` - Contém
- `contains_not` - Não contém
- `greater_than` - Maior que
- `less_than` - Menor que

### Novos Operadores - Estado
- `is_empty` - Está vazio (null, undefined, ou string vazia)
- `is_not_empty` - Não está vazio
- `is_checked` - Está marcado (checkbox/radio)
- `is_not_checked` - Não está marcado

### Novos Operadores - Padrão
- `starts_with` - Começa com
- `ends_with` - Termina com
- `regex` - Corresponde à expressão regular
- `equals_any` - Igual a qualquer valor separado por vírgula

### Novos Operadores - Data
- `date_is` - Data é igual a
- `date_before` - Data é antes de
- `date_after` - Data é depois de

## Migração de Estrutura

Os testes também verificam a migração automática de formulários antigos para a nova estrutura:

**Formato antigo (3 elementos):**
```javascript
[
  [['field1', 'equal_to', 'value']], // conditions
  [['show_fields', 'field2']],        // actions
  'and'                               // operator
]
```

**Formato novo (4 elementos):**
```javascript
[
  [['field1', 'equal_to', 'value']], // conditions
  [['show_fields', 'field2']],        // actions
  'and',                              // operator
  {                                   // metadata
    logic_id: 'uuid',
    logic_name: 'Nome opcional',
    group_id: 'group_uuid',
    enabled: true
  }
]
```

## Correções de Bugs Aplicadas

### Tratamento de valores undefined/null
Os operadores `contains`, `contains_not`, `starts_with`, `ends_with`, `regex`, e `equals_any` foram corrigidos para tratar valores `undefined` e `null` corretamente, evitando erros do tipo `Cannot read property 'toString' of undefined`.

### Arquivos Modificados
- `assets/js/src/form.js` - Correção dos operadores para tratar null/undefined
- `dist/form.min.js` - Versão compilada com as correções

## Cobertura de Testes

Atualmente existem 24 testes cobrindo:
- 4 testes de operadores de estado
- 4 testes de operadores de padrão
- 3 testes de operadores de data
- 6 testes de regressão (operadores originais)
- 4 testes de edge cases
- 3 testes de migração de estrutura
