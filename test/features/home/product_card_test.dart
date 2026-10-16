import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';
import 'package:pos_mobile/features/home/ui/widgets/product_card.dart';

void main() {
  testWidgets('ProductCard displays name, barcode, brand, and price',
      (tester) async {
    const product = Product(
      id: 1,
      nameEn: 'Veritatis Plates',
      nameAr: 'اطباق',
      code: 'PLA-4731',
      originalCode: '8462047073676',
      brand: 'Pacocha-Gottlieb',
      retailprice: 986.37,
      wholeprice: 793.91,
    );

    await tester.pumpWidget(
      const MaterialApp(
        home: Scaffold(
          body: SizedBox(
            width: 200,
            height: 300,
            child: ProductCard(product: product),
          ),
        ),
      ),
    );

    // Verify Name
    expect(find.text('Veritatis Plates'), findsOneWidget);

    // Verify Barcode / Original Code
    expect(find.text('8462047073676'), findsOneWidget);

    // Verify Brand
    expect(find.text('Pacocha-Gottlieb'), findsOneWidget);

    // Verify Price
    expect(find.text('\$986.37'), findsOneWidget);
  });
}

