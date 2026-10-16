import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:pos_mobile/screens/common/custom_text_form_field.dart';

void main() {
  testWidgets('CustomTextFormField renders label and accepts text input', (tester) async {
    final controller = TextEditingController();

    await tester.pumpWidget(
      MaterialApp(
        home: Scaffold(
          body: CustomTextFormField(
            controller: controller,
            label: 'Customer Name',
            hintText: 'Enter name',
            prefixIcon: const Icon(Icons.person),
          ),
        ),
      ),
    );

    expect(find.text('Customer Name'), findsOneWidget);
    expect(find.byIcon(Icons.person), findsOneWidget);

    await tester.enterText(find.byType(TextFormField), 'Jane Doe');
    expect(controller.text, equals('Jane Doe'));
  });
}

