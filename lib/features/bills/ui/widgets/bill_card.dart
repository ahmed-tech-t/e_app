import 'package:flutter/material.dart';
import 'package:pos_mobile/core/theme/app_decorations.dart';
import 'package:pos_mobile/features/bills/domain/models/bill.dart';
import 'package:pos_mobile/features/bills/domain/models/sale_item.dart';
import 'package:pos_mobile/features/bills/domain/services/bill_pdf_service.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';

class BillCard extends StatefulWidget {
  final Bill bill;
  final List<Product>? products;
  final VoidCallback? onTap;

  const BillCard({
    super.key,
    required this.bill,
    this.products,
    this.onTap,
  });

  @override
  State<BillCard> createState() => _BillCardState();
}

class _BillCardState extends State<BillCard> {
  bool _isExpanded = false;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final bill = widget.bill;
    final grandTotal = bill.grandTotal ?? bill.total ?? 0.0;
    final itemCount = bill.items.length;
    final billCode = bill.code != null && bill.code!.isNotEmpty
        ? bill.code!
        : 'Bill #${bill.id ?? '-'}';

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
      decoration: AppDecorations.cardDecoration(context),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: AppDecorations.borderRadiusMedium,
          onTap: () {
            setState(() {
              _isExpanded = !_isExpanded;
            });
            widget.onTap?.call();
          },
          child: Padding(
            padding: const EdgeInsets.all(14.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Top Row: Code, Actions, Total
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: theme.colorScheme.primaryContainer,
                              borderRadius: AppDecorations.borderRadiusSmall,
                            ),
                            child: Icon(
                              Icons.receipt_long_rounded,
                              size: 20,
                              color: theme.colorScheme.primary,
                            ),
                          ),
                          const SizedBox(width: 10),
                          Flexible(
                            child: Text(
                              billCode,
                              style: theme.textTheme.titleMedium?.copyWith(
                                fontWeight: FontWeight.bold,
                                letterSpacing: 0.3,
                              ),
                              overflow: TextOverflow.ellipsis,
                            ),
                          ),
                        ],
                      ),
                    ),
                    Row(
                      children: [
                        IconButton(
                          icon: const Icon(Icons.picture_as_pdf_outlined, size: 20),
                          tooltip: 'View / Print PDF',
                          onPressed: () => BillPdfService.printOrPreviewReceipt(
                            context,
                            bill,
                            products: widget.products,
                          ),
                        ),
                        IconButton(
                          icon: const Icon(Icons.share_outlined, size: 20),
                          tooltip: 'Share Bill PDF',
                          onPressed: () => BillPdfService.shareReceipt(
                            bill,
                            products: widget.products,
                          ),
                        ),
                        const SizedBox(width: 4),
                        Text(
                          '\$${grandTotal.toStringAsFixed(2)}',
                          style: theme.textTheme.titleMedium?.copyWith(
                            fontWeight: FontWeight.bold,
                            color: theme.colorScheme.primary,
                          ),
                        ),
                        const SizedBox(width: 4),
                        AnimatedRotation(
                          turns: _isExpanded ? 0.5 : 0.0,
                          duration: const Duration(milliseconds: 200),
                          child: Icon(
                            Icons.keyboard_arrow_down_rounded,
                            size: 22,
                            color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
                const Divider(height: 18),

                // Customer & Item Count Row
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          bill.customerName ?? 'Walk-in Customer',
                          style: theme.textTheme.bodyMedium?.copyWith(
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        if (bill.customerPhone != null && bill.customerPhone!.isNotEmpty)
                          Text(
                            bill.customerPhone!,
                            style: theme.textTheme.bodySmall?.copyWith(
                              color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                            ),
                          ),
                      ],
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      decoration: BoxDecoration(
                        color: theme.colorScheme.surfaceContainerHighest,
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Text(
                        '$itemCount ${itemCount == 1 ? 'item' : 'items'}',
                        style: theme.textTheme.labelSmall?.copyWith(
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                  ],
                ),

                // Animated Visibility: Bill Items and Quantity Breakdown
                AnimatedCrossFade(
                  firstChild: const SizedBox.shrink(),
                  secondChild: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const SizedBox(height: 12),
                      const Divider(height: 1),
                      const SizedBox(height: 8),
                      Text(
                        'Items & Quantities',
                        style: TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                          color: theme.colorScheme.onSurface.withValues(alpha: 0.7),
                        ),
                      ),
                      const SizedBox(height: 6),
                      ...bill.items.map((item) {
                        final itemPrice = item.price ?? 0.0;
                        final itemTotal = item.total ?? (itemPrice * (item.quantity ?? 1));

                        Product? matched;
                        if (widget.products != null && item.productId != null) {
                          for (final p in widget.products!) {
                            if (p.id == item.productId) {
                              matched = p;
                              break;
                            }
                          }
                        }

                        final name = matched != null
                            ? ((matched.nameEn?.isNotEmpty ?? false)
                                ? matched.nameEn!
                                : (matched.nameAr ?? 'Product #${item.productId ?? '-'}'))
                            : 'Product #${item.productId ?? '-'}';
                        final code = matched?.originalCode ?? matched?.code;

                        return Padding(
                          padding: const EdgeInsets.symmetric(vertical: 4.0),
                          child: Row(
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 3),
                                decoration: BoxDecoration(
                                  color: theme.colorScheme.primary.withValues(alpha: 0.1),
                                  borderRadius: BorderRadius.circular(6),
                                ),
                                child: Text(
                                  '×${item.quantity ?? 1}',
                                  style: TextStyle(
                                    fontWeight: FontWeight.bold,
                                    fontSize: 12,
                                    color: theme.colorScheme.primary,
                                  ),
                                ),
                              ),
                              const SizedBox(width: 10),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      name,
                                      style: const TextStyle(
                                        fontSize: 13,
                                        fontWeight: FontWeight.w500,
                                      ),
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                    if (code != null && code.isNotEmpty)
                                      Row(
                                        children: [
                                          Icon(
                                            Icons.qr_code_2_rounded,
                                            size: 12,
                                            color: theme.colorScheme.onSurface.withValues(alpha: 0.5),
                                          ),
                                          const SizedBox(width: 3),
                                          Text(
                                            code,
                                            style: TextStyle(
                                              fontSize: 11,
                                              color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                                              fontFamily: 'monospace',
                                            ),
                                          ),
                                        ],
                                      ),
                                  ],
                                ),
                              ),
                              Text(
                                '\$${itemTotal.toStringAsFixed(2)}',
                                style: const TextStyle(
                                  fontSize: 13,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ],
                          ),
                        );
                      }),
                    ],
                  ),
                  crossFadeState: _isExpanded
                      ? CrossFadeState.showSecond
                      : CrossFadeState.showFirst,
                  duration: const Duration(milliseconds: 250),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

/// Widget Preview
Widget previewBillCard() {
  return const MaterialApp(
    home: Scaffold(
      body: Center(
        child: BillCard(
          bill: Bill(
            id: 1,
            code: 'SAL-6835',
            customerName: 'Alice Smith',
            customerPhone: '0123456789',
            total: 150.0,
            grandTotal: 150.0,
            items: [
              SaleItem(id: 1, productId: 1, quantity: 2, price: 75.0, total: 150.0),
            ],
          ),
        ),
      ),
    ),
  );
}
