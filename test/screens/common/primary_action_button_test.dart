import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:pos_mobile/screens/common/primary_action_button.dart';

void main() {
  testWidgets('PrimaryActionButton displays label and handles tap', (tester) async {
    bool tapped = false;

    await tester.pumpWidget(
      MaterialApp(
        home: Scaffold(
          body: PrimaryActionButton(
            label: 'Submit Order',
            onPressed: () => tapped = true,
          ),
        ),
      ),
    );

    expect(find.text('Submit Order'), findsOneWidget);
    await tester.tap(find.text('Submit Order'));
    expect(tapped, isTrue);
  });

  testWidgets('PrimaryActionButton shows loading spinner and disables tap when isLoading is true',
      (tester) async {
    bool tapped = false;

    await tester.pumpWidget(
      MaterialApp(
        home: Scaffold(
          body: PrimaryActionButton(
            label: 'Loading Order',
            isLoading: true,
            onPressed: () => tapped = true,
          ),
        ),
      ),
    );

    expect(find.byType(CircularProgressIndicator), findsOneWidget);
    await tester.tap(find.byType(PrimaryActionButton));
    expect(tapped, isFalse);
  });
}

