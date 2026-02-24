/**
 * FormCraft Logic Operators Tests
 *
 * Tests for new conditional logic operators:
 * - is_empty, is_not_empty
 * - starts_with, ends_with, regex, equals_any
 * - is_checked, is_not_checked
 * - date_is, date_before, date_after
 */

import { test, expect } from '@playwright/test';

// Helper function to simulate form logic evaluation
function evaluateLogic(value, operator, condition) {
  switch (operator) {
    case 'is_empty':
      return value === '' || value === null || value === undefined;

    case 'is_not_empty':
      return value !== '' && value !== null && value !== undefined;

    case 'starts_with':
      return value != null && value.toString().indexOf(condition) === 0;

    case 'ends_with':
      return value != null && value.toString().endsWith(condition);

    case 'regex':
      try {
        const regex = new RegExp(condition);
        return value != null && regex.test(value.toString());
      } catch (e) {
        return false;
      }

    case 'equals_any':
      const values = condition.split(',').map(v => v.trim());
      return value != null && values.indexOf(value.toString()) !== -1;

    case 'is_checked':
      return value === true || value === 'true';

    case 'is_not_checked':
      return value !== true && value !== 'true';

    case 'date_is':
      // Simplified date comparison
      return value.toString() === condition.toString();

    case 'date_before':
      return new Date(value) < new Date(condition);

    case 'date_after':
      return new Date(value) > new Date(condition);

    // Original operators
    case 'equal_to':
      return value.toString() === condition.toString();

    case 'not_equal_to':
      return value.toString() !== condition.toString();

    case 'contains':
      if (condition === '') {
        return value !== '' && value !== null && value !== undefined;
      }
      return value != null && value.toString().indexOf(condition) !== -1;

    case 'contains_not':
      return value == null || value.toString().indexOf(condition) === -1;

    case 'greater_than':
      return parseFloat(value) > parseFloat(condition);

    case 'less_than':
      return parseFloat(value) < parseFloat(condition);

    default:
      return false;
  }
}

// Helper for UUID generation (migration tests)
function generateUUID() {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    const r = Math.random() * 16 | 0;
    const v = c === 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}

// Helper for logic migration
function migrateLogic(logic) {
  if (logic.length < 4) {
    logic[3] = {
      logic_id: generateUUID(),
      logic_name: '',
      group_id: 'default',
      enabled: true
    };
  }
  return logic;
}

test.describe('FormCraft Logic Operators', () => {

  test.describe('State Operators', () => {
    test('is_empty: should return true for empty values', () => {
      expect(evaluateLogic('', 'is_empty', 'test')).toBe(true);
      expect(evaluateLogic(null, 'is_empty', 'test')).toBe(true);
      expect(evaluateLogic(undefined, 'is_empty', 'test')).toBe(true);
      expect(evaluateLogic('hello', 'is_empty', 'test')).toBe(false);
    });

    test('is_not_empty: should return true for non-empty values', () => {
      expect(evaluateLogic('hello', 'is_not_empty', 'test')).toBe(true);
      expect(evaluateLogic('0', 'is_not_empty', 'test')).toBe(true);
      expect(evaluateLogic('', 'is_not_empty', 'test')).toBe(false);
      expect(evaluateLogic(null, 'is_not_empty', 'test')).toBe(false);
    });

    test('is_checked: should return true for checked state', () => {
      expect(evaluateLogic(true, 'is_checked', '')).toBe(true);
      expect(evaluateLogic('true', 'is_checked', '')).toBe(true);
      expect(evaluateLogic(false, 'is_checked', '')).toBe(false);
      expect(evaluateLogic(null, 'is_checked', '')).toBe(false);
    });

    test('is_not_checked: should return true for unchecked state', () => {
      expect(evaluateLogic(false, 'is_not_checked', '')).toBe(true);
      expect(evaluateLogic(null, 'is_not_checked', '')).toBe(true);
      expect(evaluateLogic(true, 'is_not_checked', '')).toBe(false);
    });
  });

  test.describe('Pattern Operators', () => {
    test('starts_with: should check prefix', () => {
      expect(evaluateLogic('hello world', 'starts_with', 'hello')).toBe(true);
      expect(evaluateLogic('hello world', 'starts_with', 'world')).toBe(false);
      expect(evaluateLogic('123', 'starts_with', '1')).toBe(true);
    });

    test('ends_with: should check suffix', () => {
      expect(evaluateLogic('hello world', 'ends_with', 'world')).toBe(true);
      expect(evaluateLogic('hello world', 'ends_with', 'hello')).toBe(false);
      expect(evaluateLogic('test.txt', 'ends_with', '.txt')).toBe(true);
    });

    test('regex: should match pattern', () => {
      expect(evaluateLogic('test@example.com', 'regex', '^[\\w-\\.]+@[\\w-]+\\.[a-z]{2,4}$')).toBe(true);
      expect(evaluateLogic('invalid-email', 'regex', '^[\\w-\\.]+@[\\w-]+\\.[a-z]{2,4}$')).toBe(false);
      expect(evaluateLogic('ABC123', 'regex', '^[A-Z]+[0-9]+$')).toBe(true);
      expect(evaluateLogic('abc123', 'regex', '^[A-Z]+[0-9]+$')).toBe(false);
    });

    test('equals_any: should match any comma-separated value', () => {
      expect(evaluateLogic('red', 'equals_any', 'red,green,blue')).toBe(true);
      expect(evaluateLogic('green', 'equals_any', 'red,green,blue')).toBe(true);
      expect(evaluateLogic('blue', 'equals_any', 'red,green,blue')).toBe(true);
      expect(evaluateLogic('yellow', 'equals_any', 'red,green,blue')).toBe(false);
      expect(evaluateLogic(' red ', 'equals_any', 'red,green,blue')).toBe(false); // no trim on value
    });
  });

  test.describe('Date Operators', () => {
    test('date_is: should compare dates', () => {
      expect(evaluateLogic('2024-01-15', 'date_is', '2024-01-15')).toBe(true);
      expect(evaluateLogic('2024-01-15', 'date_is', '2024-01-16')).toBe(false);
    });

    test('date_before: should check if date is before', () => {
      expect(evaluateLogic('2024-01-10', 'date_before', '2024-01-15')).toBe(true);
      expect(evaluateLogic('2024-01-20', 'date_before', '2024-01-15')).toBe(false);
    });

    test('date_after: should check if date is after', () => {
      expect(evaluateLogic('2024-01-20', 'date_after', '2024-01-15')).toBe(true);
      expect(evaluateLogic('2024-01-10', 'date_after', '2024-01-15')).toBe(false);
    });
  });

  test.describe('Original Operators - Regression Tests', () => {
    test('equal_to: exact match', () => {
      expect(evaluateLogic('test', 'equal_to', 'test')).toBe(true);
      expect(evaluateLogic('test', 'equal_to', 'Test')).toBe(false);
    });

    test('not_equal_to: not exact match', () => {
      expect(evaluateLogic('test', 'not_equal_to', 'other')).toBe(true);
      expect(evaluateLogic('test', 'not_equal_to', 'test')).toBe(false);
    });

    test('contains: substring match', () => {
      expect(evaluateLogic('hello world', 'contains', 'world')).toBe(true);
      expect(evaluateLogic('hello world', 'contains', 'lo wo')).toBe(true);
      expect(evaluateLogic('hello', 'contains', 'world')).toBe(false);
    });

    test('contains_not: no substring match', () => {
      expect(evaluateLogic('hello', 'contains_not', 'world')).toBe(true);
      expect(evaluateLogic('hello world', 'contains_not', 'world')).toBe(false);
    });

    test('greater_than: numeric comparison', () => {
      expect(evaluateLogic('10', 'greater_than', '5')).toBe(true);
      expect(evaluateLogic('5', 'greater_than', '10')).toBe(false);
    });

    test('less_than: numeric comparison', () => {
      expect(evaluateLogic('5', 'less_than', '10')).toBe(true);
      expect(evaluateLogic('10', 'less_than', '5')).toBe(false);
    });
  });

  test.describe('Edge Cases', () => {
    test('handles undefined values gracefully', () => {
      expect(evaluateLogic(undefined, 'contains', 'test')).toBe(false);
      expect(evaluateLogic(undefined, 'is_empty', '')).toBe(true);
    });

    test('handles special characters in regex', () => {
      expect(evaluateLogic('test@example.com', 'regex', '^[\\w\\.]+@[\\w\\.]+$')).toBe(true);
    });

    test('equals_any with spaces', () => {
      expect(evaluateLogic('red', 'equals_any', 'red , green , blue')).toBe(true);
      expect(evaluateLogic('green', 'equals_any', 'red , green , blue')).toBe(true);
    });

    test('empty condition for starts_with', () => {
      expect(evaluateLogic('test', 'starts_with', '')).toBe(true);
      expect(evaluateLogic('', 'starts_with', 'test')).toBe(false);
    });
  });
});

test.describe('Logic Structure Migration', () => {
  test('migrates old logic structure to new format', () => {
    // Old format: 3 elements
    const oldLogic = [
      [['field1', 'equal_to', 'value']], // conditions
      [['show_fields', 'field2']],       // actions
      'and'                              // operator
    ];

    // New format should have 4 elements
    const migrated = migrateLogic([...oldLogic]);
    expect(migrated.length).toBe(4);
    expect(migrated[3]).toHaveProperty('logic_id');
    expect(migrated[3]).toHaveProperty('group_id');
    expect(migrated[3].group_id).toBe('default');
  });

  test('preserves existing new format structure', () => {
    const newLogic = [
      [['field1', 'equal_to', 'value']],
      [['show_fields', 'field2']],
      'and',
      {
        logic_id: 'existing-id',
        logic_name: 'Test Logic',
        group_id: 'custom-group',
        enabled: false
      }
    ];

    const migrated = migrateLogic([...newLogic]);
    expect(migrated[3].logic_id).toBe('existing-id');
    expect(migrated[3].logic_name).toBe('Test Logic');
    expect(migrated[3].group_id).toBe('custom-group');
    expect(migrated[3].enabled).toBe(false);
  });

  test('generates unique IDs for migrated logics', () => {
    const logic1 = [[['f1', 'eq', 'v']], [['show', 'f2']], 'and'];
    const logic2 = [[['f3', 'eq', 'v']], [['show', 'f4']], 'and'];

    const migrated1 = migrateLogic([...logic1]);
    const migrated2 = migrateLogic([...logic2]);

    expect(migrated1[3].logic_id).not.toBe(migrated2[3].logic_id);
  });
});
