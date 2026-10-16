import 'package:flutter/material.dart';
import 'package:pos_mobile/core/theme/app_decorations.dart';
import 'package:pos_mobile/features/bills/domain/models/bill.dart';
import 'package:pos_mobile/features/bills/domain/models/sale_item.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';
import 'package:pos_mobile/screens/common/primary_action_button.dart';

class BillPreviewDialog extends StatelessWidget {
  final Bill bill;
  final List<Product>? products;
  final bool isSubmitting;
  final VoidCallback onConfirm;

  const BillPreviewDialog({
    super.key,
    required this.bill,
    this.products,
    required this.isSubmitting,
    required this.onConfirm,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final items = bill.items;
    final total = bill.total ?? 0.0;
    final discount = bill.discount ?? 0.0;
    final tax = bill.tax ?? 15.0;
    final grandTotal = bill.grandTotal ?? total;
    final taxAmount = (total - (total * (discount / 100))) * (tax / 100);

    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: AppDecorations.borderRadiusLarge),
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 500, maxHeight: 650),
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Header
              Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: theme.colorScheme.primaryContainer,
                      borderRadius: AppDecorations.borderRadiusSmall,
                    ),
                    child: Icon(Icons.receipt_long, color: theme.colorScheme.primary),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Bill Preview',
                          style: theme.textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
                        ),
                        Text(
                          'Calculated on server',
                          style: TextStyle(
                            color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                            fontSize: 12,
                          ),
                        ),
                      ],
                    ),
                  ),
                  IconButton(
                    icon: const Icon(Icons.close),
                    onPressed: isSubmitting ? null : () => Navigator.of(context).pop(),
                  ),
                ],
              ),
              const SizedBox(height: 14),

              // Customer Info Card
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: theme.colorScheme.surfaceContainerHighest.withValues(alpha: 0.5),
                  borderRadius: AppDecorations.borderRadiusSmall,
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          bill.customerName ?? 'Walk-in Customer',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        if (bill.customerPhone != null && bill.customerPhone!.isNotEmpty)
                          Text(
                            bill.customerPhone!,
                            style: TextStyle(
                              color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                              fontSize: 12,
                            ),
                          ),
                      ],
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: theme.colorScheme.primary.withValues(alpha: 0.12),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Text(
                        '${items.length} items',
                        style: TextStyle(
                          color: theme.colorScheme.primary,
                          fontWeight: FontWeight.w600,
                          fontSize: 12,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 12),

              // Items List
              Expanded(
                child: ListView.separated(
                  shrinkWrap: true,
                  itemCount: items.length,
                  separatorBuilder: (context, index) => const Divider(height: 1),
                  itemBuilder: (context, index) {
                    final item = items[index];
                    final itemTotal = item.total ?? ((item.price ?? 0) * (item.quantity ?? 1));

                    Product? matchedProduct;
                    if (products != null && item.productId != null) {
                      for (final p in products!) {
                        if (p.id == item.productId) {
                          matchedProduct = p;
                          break;
                        }
                      }
                    }

                    final displayName = matchedProduct != null
                        ? ((matchedProduct.nameEn?.isNotEmpty ?? false)
                            ? matchedProduct.nameEn!
                            : (matchedProduct.nameAr ?? 'Product #${item.productId ?? '-'}'))
                        : 'Product #${item.productId ?? '-'}';

                    final barcode = matchedProduct?.originalCode ?? matchedProduct?.code;

                    return Padding(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      child: Row(
                        children: [
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  displayName,
                                  style: const TextStyle(fontWeight: FontWeight.w600),
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                ),
                                if (barcode != null && barcode.isNotEmpty) ...[
                                  const SizedBox(height: 2),
                                  Row(
                                    children: [
                                      Icon(
                                        Icons.qr_code_2_rounded,
                                        size: 13,
                                        color: theme.colorScheme.onSurface.withValues(alpha: 0.5),
                                      ),
                                      const SizedBox(width: 4),
                                      Text(
                                        barcode,
                                        style: TextStyle(
                                          color: theme.colorScheme.onSurface.withValues(alpha: 0.7),
                                          fontSize: 12,
                                          fontFamily: 'monospace',
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                                const SizedBox(height: 2),
                                Text(
                                  'Qty: ${item.quantity ?? 1} × \$${(item.price ?? 0).toStringAsFixed(2)}',
                                  style: TextStyle(
                                    color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                                    fontSize: 12,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          Text(
                            '\$${itemTotal.toStringAsFixed(2)}',
                            style: const TextStyle(fontWeight: FontWeight.bold),
                          ),
                        ],
                      ),
                    );
                  },
                ),
              ),
              const Divider(thickness: 1.2),

              // Summary Section
              _buildPriceRow(context, 'Subtotal', '\$${total.toStringAsFixed(2)}'),
              _buildPriceRow(context, 'Discount', '${discount.toStringAsFixed(1)}%'),
              _buildPriceRow(context, 'Tax (${tax.toStringAsFixed(0)}%)', '\$${taxAmount.toStringAsFixed(2)}'),
              const SizedBox(height: 4),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Grand Total',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                  Text(
                    '\$${grandTotal.toStringAsFixed(2)}',
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: theme.colorScheme.primary,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 18),

              // Actions
              Row(
                children: [
                  Expanded(
                    child: OutlinedButton(
                      onPressed: isSubmitting ? null : () => Navigator.of(context).pop(),
                      child: const Text('Edit Bill'),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    flex: 2,
                    child: PrimaryActionButton(
                      label: isSubmitting ? 'Completing...' : 'Confirm & Complete',
                      isLoading: isSubmitting,
                      icon: const Icon(Icons.check_circle_outline, color: Colors.white, size: 18),
                      onPressed: isSubmitting ? null : onConfirm,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildPriceRow(BuildContext context, String label, String value) {
    final theme = Theme.of(context);
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 2),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: TextStyle(
              color: theme.colorScheme.onSurface.withValues(alpha: 0.7),
              fontSize: 13,
            ),
          ),
          Text(value, style: const TextStyle(fontWeight: FontWeight.w500, fontSize: 13)),
        ],
      ),
    );
  }
}

/// Widget Preview
Widget previewBillPreviewDialog() {
  return MaterialApp(
    home: Scaffold(
      body: Center(
        child: BillPreviewDialog(
          bill: const Bill(
            id: 1,
            code: 'SAL-1234',
            customerName: 'John Doe',
            total: 200.0,
            discount: 10.0,
            tax: 15.0,
            grandTotal: 207.0,
            items: [
              SaleItem(id: 1, productId: 1, quantity: 2, price: 100.0, total: 200.0),
            ],
          ),
          isSubmitting: false,
          onConfirm: () {},
        ),
      ),
    ),
  );
}
